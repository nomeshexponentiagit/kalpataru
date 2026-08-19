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
	<div class="admin">
		<aside class="side">
			<a class="side__brand" href="/admin/">Kalpataru<span>.</span></a>
			<nav class="side__nav">
				<a class="side__link <?= $active === 'leads' ? 'is-active' : '' ?>" href="/admin/">Leads</a>
				<span class="side__link" style="opacity:.45;cursor:default;" title="Coming soon">Work cards</span>
				<span class="side__link" style="opacity:.45;cursor:default;" title="Coming soon">Logo &amp; settings</span>
			</nav>
			<div class="side__foot">
				Logged in as <strong><?= e($_SESSION['admin_user'] ?? '') ?></strong><br />
				<a href="/admin/logout.php">Log out</a>
			</div>
		</aside>
		<main class="main">
