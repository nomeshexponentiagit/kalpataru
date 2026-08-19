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
			<h1>Kalpataru<span>.</span></h1>
			<p class="muted" style="margin-bottom:1.4rem">Website control panel — staff only.</p>

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
			<button class="btn" type="submit" style="width:100%;justify-content:center">Log in</button>
		</form>
	</div>
</body>
</html>
