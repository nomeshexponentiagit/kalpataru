<?php
/** Shared admin page shell — expects $title and $active ('leads' for now).
 *  Call require_login() before including this file. */
$title  = $title ?? 'Admin';
$active = $active ?? '';
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="robots" content="noindex, nofollow" />
	<title><?= e($title) ?> — Kalpataru Admin</title>
	<link rel="stylesheet" href="/admin/assets/admin.css" />
</head>
<body>
<div class="app">
	<button class="side-toggle" type="button" data-side-toggle aria-label="Open menu" aria-expanded="false">
		<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16" /></svg>
	</button>
	<div class="side-backdrop" data-side-backdrop></div>
	<aside class="side" data-side>
		<div class="side__top">
			<a class="side__brand" href="/admin/">
				<span class="side__logo">K</span>
				<span class="side__brand-text">
					<strong>Kalpataru</strong>
					<span>Admin panel</span>
				</span>
			</a>
		</div>
		<nav class="side__nav">
			<p class="side__label">Manage</p>
			<a class="side__link <?= $active === 'leads' ? 'is-active' : '' ?>" href="/admin/">
				<span class="side__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-6l-2 3h-4l-2-3H2" /><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z" /></svg></span>
				<span>Leads</span>
			</a>
			<span class="side__link is-disabled" title="Coming soon">
				<span class="side__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2" /><polyline points="2 17 12 22 22 17" /><polyline points="2 12 12 17 22 12" /></svg></span>
				<span>Work cards</span>
				<span class="side__soon">Soon</span>
			</span>
			<span class="side__link is-disabled" title="Coming soon">
				<span class="side__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3" /><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" /></svg></span>
				<span>Logo &amp; settings</span>
				<span class="side__soon">Soon</span>
			</span>
		</nav>
		<div class="side__foot">
			<div class="side__user">
				<span class="avatar"><?= e(strtoupper(substr((string) ($_SESSION['admin_user'] ?? 'A'), 0, 1))) ?></span>
				<span class="side__user-meta">
					<strong><?= e($_SESSION['admin_user'] ?? 'admin') ?></strong>
					<span>Owner</span>
				</span>
				<a class="side__logout" href="/admin/logout.php" title="Log out">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" /><polyline points="16 17 21 12 16 7" /><line x1="21" y1="12" x2="9" y2="12" /></svg>
				</a>
			</div>
		</div>
	</aside>
	<main class="main">
