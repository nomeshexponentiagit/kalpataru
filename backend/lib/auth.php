<?php
/** Admin sessions: login/logout, CSRF protection, login throttling. */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers.php';

/** Start (or resume) the session with hardened cookie flags. */
function session_start_secure(): void
{
	if (session_status() === PHP_SESSION_ACTIVE) return;

	$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
	session_set_cookie_params([
		'lifetime' => 0,               // until the browser closes
		'path'     => '/',
		'secure'   => $secure,         // HTTPS-only in production
		'httponly' => true,            // JS can't read the session cookie
		'samesite' => 'Lax',
	]);
	session_name('KALADMIN');
	session_start();
}

/** True when an admin is logged in (and the session isn't stale). */
function is_logged_in(): bool
{
	return !empty($_SESSION['admin_user']);
}

/** Halt unless logged in — used at the top of every admin page. */
function require_login(): void
{
	session_start_secure();
	if (!is_logged_in()) {
		header('Location: ' . base_url('/admin/login.php'));
		exit;
	}
}

/** Attempt a login. Returns true on success; on failure sleeps (throttle). */
function try_login(string $user, string $password): bool
{
	session_start_secure();

	// slow down brute force: 5 failed attempts → progressively longer sleeps
	$_SESSION['login_fails'] = ($_SESSION['login_fails'] ?? 0);
	if ($_SESSION['login_fails'] >= 5) {
		sleep(2);
	}

	$userOk = hash_equals(ADMIN_USER, $user);
	$passOk = $userOk && ADMIN_PASSWORD_HASH !== ''
		&& password_verify($password, ADMIN_PASSWORD_HASH);

	if ($userOk && $passOk) {
		session_regenerate_id(true); // prevent session fixation
		$_SESSION['admin_user'] = $user;
		unset($_SESSION['login_fails']);
		return true;
	}

	$_SESSION['login_fails']++;
	sleep(1);
	return false;
}

function logout(): void
{
	session_start_secure();
	$_SESSION = [];
	if (ini_get('session.use_cookies')) {
		$p = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
	}
	session_destroy();
}

/** CSRF token for the current session (embedded in every admin form). */
function csrf_token(): string
{
	if (empty($_SESSION['csrf'])) {
		$_SESSION['csrf'] = bin2hex(random_bytes(32));
	}
	return $_SESSION['csrf'];
}

/** Reject POSTs whose CSRF token doesn't match (blocks cross-site forgery). */
function csrf_check(): void
{
	$sent = $_POST['csrf'] ?? '';
	if (!is_string($sent) || !hash_equals($_SESSION['csrf'] ?? '', $sent)) {
		http_response_code(403);
		exit('Invalid request (CSRF). Go back and try again.');
	}
}
