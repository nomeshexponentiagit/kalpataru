<?php
/** Admin login. */
require_once __DIR__ . '/../lib/auth.php';

session_start_secure();

// already logged in → straight to the dashboard
if (is_logged_in()) {
	header('Location: /admin/');
	exit;
}

$error = '';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
	csrf_check();
	if (try_login($_POST['user'] ?? '', $_POST['password'] ?? '')) {
		header('Location: /admin/');
		exit;
	}
	$error = 'Wrong username or password.';
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="robots" content="noindex, nofollow" />
	<title>Log in — Kalpataru Admin</title>
	<link rel="stylesheet" href="/admin/assets/admin.css" />
</head>
<body>
	<div class="login-wrap">
		<form class="login-card" method="post" action="/admin/login.php">
			<div class="login-brand">
				<span class="login-logo">K</span>
				<div>
					<h1>Kalpataru</h1>
					<p class="login-sub">Website control panel — staff only</p>
				</div>
			</div>

			<?php if ($error !== ''): ?>
				<div class="alert alert--bad"><?= e($error) ?></div>
			<?php endif; ?>

			<div class="field">
				<label for="user">Username</label>
				<input class="input" id="user" name="user" type="text" autocomplete="username" required autofocus />
			</div>
			<div class="field">
				<label for="password">Password</label>
				<input class="input" id="password" name="password" type="password" autocomplete="current-password" required />
			</div>

			<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>" />
			<button class="btn btn--primary btn--block" type="submit">Log in</button>

			<p class="login-foot"><a href="/">&larr; Back to the website</a></p>
		</form>
	</div>
</body>
</html>
