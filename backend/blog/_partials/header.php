<?php
/** Shared blog shell — replicates the site's fixed header + mobile menu.
 *  Expects $pageTitle and $pageDescription. No Blog link here: the Blog
 *  link lives in the footer only (user request). */
$pageTitle       = $pageTitle ?? 'Blog';
$pageDescription = $pageDescription ?? '';

$blogNav = [
	['Work', '/work'],
	['Services', '/services'],
	['Industries', '/industries'],
	['About', '/about'],
	['Contact', '/contact'],
];
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?= e($pageTitle) ?></title>
	<meta name="description" content="<?= e($pageDescription) ?>" />
	<meta property="og:title" content="<?= e($pageTitle) ?>" />
	<meta property="og:description" content="<?= e($pageDescription) ?>" />
	<meta property="og:type" content="website" />
	<link rel="icon" href="/favicon.svg" type="image/svg+xml" />
	<link rel="icon" href="/favicon.ico" sizes="any" />
	<link rel="stylesheet" href="/blog/assets/blog.css" />
</head>
<body>
<a class="skip-link" href="#main">Skip to content</a>
<header class="site-header" data-header>
	<div class="container site-header__row">
		<a class="site-header__brand" href="/" aria-label="Kalpataru Exhibition — home">
			<img class="site-header__logo" src="/brand/logo.png" width="200" height="48" alt="Kalpataru Exhibition" />
		</a>
		<nav class="site-header__nav" aria-label="Primary">
			<ul class="site-header__list">
				<?php foreach ($blogNav as [$bl, $bh]): ?>
					<li><a class="site-header__link" href="<?= e($bh) ?>"><?= e($bl) ?></a></li>
				<?php endforeach; ?>
			</ul>
		</nav>
		<div class="site-header__cta">
			<a class="btn btn--solid btn--sm" href="/contact">
				<span class="btn__label">Start a project</span>
				<span class="btn__arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14" /><path d="m13 6 6 6-6 6" /></svg></span>
			</a>
		</div>
		<button class="site-header__burger" type="button" data-menu-toggle aria-expanded="false" aria-controls="site-menu" aria-label="Open menu">
			<span></span><span></span>
		</button>
	</div>
</header>
<div class="site-menu" id="site-menu" data-menu>
		<nav class="site-menu__nav" aria-label="Menu">
			<ul>
				<?php $menuI = 0; foreach ($blogNav as [$bl, $bh]): ?>
					<li style="--i:<?= $menuI++ ?>"><a class="site-menu__link" href="<?= e($bh) ?>"><?= e($bl) ?></a></li>
				<?php endforeach; ?>
			</ul>
		</nav>
		<div class="site-menu__foot">
			<a class="btn btn--solid" href="/contact">
				<span class="btn__label">Start a project</span>
				<span class="btn__arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14" /><path d="m13 6 6 6-6 6" /></svg></span>
			</a>
			<p class="site-menu__markets">India · Japan · China · USA</p>
		</div>
	</div>
<main id="main">
