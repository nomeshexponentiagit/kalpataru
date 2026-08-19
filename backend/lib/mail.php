<?php
/** Lead notification email — plain text, sent with PHP's mail() which
 *  Hostinger supports out of the box. If delivery proves unreliable in
 *  production, swap this for SMTP (PHPMailer) using the same $to/$subject/$body. */

require_once __DIR__ . '/config.php';

/**
 * Email a new enquiry to the company inbox. Never throws — a mail problem
 * must not block the lead from being stored.
 */
function notify_lead(array $lead): void
{
	if (NOTIFY_EMAIL === '' || str_starts_with(NOTIFY_EMAIL, 'CHANGE_ME')) {
		return;
	}

	$subject = 'New enquiry — ' . $lead['name'] . ($lead['company'] !== '' ? ' (' . $lead['company'] . ')' : '');
	$body    = "New enquiry from the website\n"
		. "----------------------------\n"
		. "Name:    {$lead['name']}\n"
		. "Email:   {$lead['email']}\n"
		. "Phone:   " . ($lead['phone'] !== '' ? $lead['phone'] : '—') . "\n"
		. "Company: " . ($lead['company'] !== '' ? $lead['company'] : '—') . "\n"
		. "Page:    " . ($lead['page'] !== '' ? $lead['page'] : '—') . "\n\n"
		. "Message:\n{$lead['message']}\n\n"
		. 'Manage this lead in the admin panel: ' . SITE_URL . '/admin/';

	$headers = [
		'From: Kalpataru Website <' . NOTIFY_FROM . '>',
		'Reply-To: ' . $lead['name'] . ' <' . $lead['email'] . '>',
		'Content-Type: text/plain; charset=UTF-8',
		'X-Mailer: PHP/' . PHP_VERSION,
	];

	try {
		@mail(NOTIFY_EMAIL, $subject, $body, implode("\r\n", $headers));
	} catch (Throwable $e) {
		// silently ignore — the lead is already in the database
	}
}
