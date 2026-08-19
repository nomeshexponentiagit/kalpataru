<?php
/** PDO singleton — every query in the backend goes through here (prepared
 *  statements only, so user input can never become SQL). */

require_once __DIR__ . '/config.php';

function db(): PDO
{
	static $pdo = null;
	if ($pdo === null) {
		$pdo = new PDO(
			'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
			DB_USER,
			DB_PASS,
			[
				PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				PDO::ATTR_EMULATE_PREPARES   => false, // real prepared statements
			]
		);
	}
	return $pdo;
}
