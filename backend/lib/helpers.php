<?php
/** Small shared helpers: escaping, JSON responses, IP hashing, input cleanup. */

require_once __DIR__ . '/config.php';

/** Escape a value for safe HTML output. */
function e(?string $s): string
{
	return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

/** Send a JSON response and stop. */
function json_out(array $data, int $status = 200): never
{
	http_response_code($status);
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	exit;
}

/** One-way hash of the visitor IP (never stored in the clear). */
function ip_hash(): string
{
	$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
	return hash('sha256', $ip . '|' . IP_SALT);
}

/** Trim + strip control chars + cap length. Returns '' if not a string. */
function clean_input(mixed $v, int $max): string
{
	if (!is_string($v)) return '';
	$v = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/u', '', trim($v));
	return mb_substr($v, 0, $max);
}

/** Validate an email address (format + obvious junk). */
function valid_email(string $email): bool
{
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;
	return strlen($email) <= 190;
}

/** One-time flash message stored in the session (for redirects). */
function flash_set(string $kind, string $text): void
{
	$_SESSION['flash'] = ['kind' => $kind, 'text' => $text];
}

function flash_take(): ?array
{
	$f = $_SESSION['flash'] ?? null;
	unset($_SESSION['flash']);
	return $f;
}

/** Full URL of a path — used for redirects that must stay in the backend. */
function base_url(string $path = ''): string
{
	$https  = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
	$scheme = $https ? 'https' : 'http';
	$host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
	return $scheme . '://' . $host . $path;
}
