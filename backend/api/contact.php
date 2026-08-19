<?php
/**
 * Contact form handler — receives the Astro contact form, validates, stores
 * the enquiry in MySQL and emails the team.
 *
 * Speaks two dialects:
 *   • JSON POST (the website's fetch call)     → JSON response
 *   • normal form POST (no-JavaScript visitors) → redirects back to /contact
 *
 * Anti-spam: honeypot field, minimum-time trap (JS path), per-IP rate limit.
 */

require_once __DIR__ . '/../lib/config.php';
require_once __DIR__ . '/../lib/db.php';
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/mail.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
	http_response_code(405);
	header('Allow: POST');
	exit('Method not allowed');
}

$isJson = str_contains($_SERVER['CONTENT_TYPE'] ?? '', 'application/json');

// ---- read input -----------------------------------------------------------
if ($isJson) {
	$raw = file_get_contents('php://input');
	$in  = json_decode($raw, true);
	if (!is_array($in)) json_out(['ok' => false, 'error' => 'Bad request'], 400);
} else {
	$in = $_POST;
}

// ---- anti-spam ------------------------------------------------------------
// Honeypot: a hidden field no human can see or fill. Bots fill it — pretend
// success so they learn nothing.
if (($in['website'] ?? '') !== '') {
	json_out(['ok' => true]);
}

// Minimum-time trap (JS path only): humans need a few seconds to fill the
// form; scripts submit instantly.
if ($isJson) {
	$ms = (int) ($in['form_ms'] ?? 0);
	if ($ms < 2500) {
		json_out(['ok' => false, 'error' => 'Submitted too quickly.'], 400);
	}
}

// Rate limit per IP.
try {
	$stmt = db()->prepare(
		'SELECT COUNT(*) FROM form_submissions WHERE ip_hash = ? AND created_at > (NOW() - INTERVAL ? SECOND)'
	);
	$stmt->execute([ip_hash(), RATE_LIMIT_WINDOW_SECONDS]);
	if ((int) $stmt->fetchColumn() >= RATE_LIMIT_MAX_SUBMISSIONS) {
		$msg = 'Too many messages from this device — please try again later or call us directly.';
		if ($isJson) json_out(['ok' => false, 'error' => $msg], 429);
		header('Location: /contact?sent=error');
		exit;
	}
} catch (Throwable $e) {
	// table missing = not yet set up; don't hard-fail, just skip the limit
}

// ---- validate --------------------------------------------------------------
$lead = [
	'name'    => clean_input($in['name'] ?? '', 120),
	'email'   => clean_input($in['email'] ?? '', 190),
	'phone'   => clean_input($in['phone'] ?? '', 40),
	'company' => clean_input($in['company'] ?? '', 160),
	'message' => clean_input($in['message'] ?? '', 5000),
	'page'    => clean_input($in['page'] ?? '', 255),
	'event'   => clean_input($in['event'] ?? '', 160),
];

// the form has an "Event / dates" field — fold it into the message
if ($lead['event'] !== '') {
	$lead['message'] = mb_substr($lead['message'] . "\n\nEvent / dates: " . $lead['event'], 0, 5000);
}
unset($lead['event']);

$errors = [];
if ($lead['name'] === '')            $errors[] = 'name';
if (!valid_email($lead['email']))    $errors[] = 'email';
if (mb_strlen($lead['message']) < 10) $errors[] = 'message';

if ($errors) {
	$msg = 'Please check the highlighted fields and try again.';
	if ($isJson) json_out(['ok' => false, 'error' => $msg, 'fields' => $errors], 400);
	header('Location: /contact?sent=error');
	exit;
}

// ---- store + notify ---------------------------------------------------------
try {
	db()->prepare(
		'INSERT INTO leads (name, email, phone, company, message, page, ip_hash)
		 VALUES (?, ?, ?, ?, ?, ?, ?)'
	)->execute([
		$lead['name'],
		$lead['email'],
		$lead['phone'],
		$lead['company'],
		$lead['message'],
		$lead['page'],
		ip_hash(),
	]);

	db()->prepare('INSERT INTO form_submissions (ip_hash) VALUES (?)')->execute([ip_hash()]);

	notify_lead($lead);
} catch (Throwable $e) {
	// Log for us; tell the visitor something generic (never leak DB errors).
	error_log('[contact.php] ' . $e->getMessage());
	if ($isJson) json_out(['ok' => false, 'error' => 'Something went wrong — please try again or call us.'], 500);
	header('Location: /contact?sent=error');
	exit;
}

if ($isJson) {
	json_out(['ok' => true]);
}
header('Location: /contact?sent=1');
exit;
