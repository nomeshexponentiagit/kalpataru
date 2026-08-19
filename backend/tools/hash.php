<?php
/**
 * Generate the admin password hash for lib/config.php.
 * Usage (terminal):  php tools/hash.php "your-strong-password"
 * Copy the printed line into ADMIN_PASSWORD_HASH.
 */

if (PHP_SAPI !== 'cli') {
	exit("Run from the terminal only.\n");
}

$password = $argv[1] ?? '';
if ($password === '') {
	exit("Usage: php tools/hash.php \"your-password\"\n");
}

echo password_hash($password, PASSWORD_DEFAULT), "\n";
