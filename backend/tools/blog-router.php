<?php
/** Router for the local staging server (php -S).
 *  Maps pretty blog URLs (/blog/<slug>) to blog/post.php the same way
 *  blog/.htaccess does on Hostinger Apache. Returns false for anything
 *  else so php -S falls back to static files. */

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

if (preg_match('#^/blog/([a-z0-9-]+)$#', (string) $path, $m) === 1) {
	$_GET['slug'] = $m[1];
	require __DIR__ . '/../blog/post.php';
	return true;
}

return false;
