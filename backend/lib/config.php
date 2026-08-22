<?php
/**
 * Kalpataru Exhibition — backend configuration.
 * Fill in the values from hPanel before uploading (see README.md).
 *
 * Local development override: create lib/config.local.php next to this file
 * with `defined(...) || define(...)` lines for the settings you want to
 * change locally (never commit it — it is gitignored).
 */

$kalpataruLocalConfig = __DIR__ . '/config.local.php';
if (is_file($kalpataruLocalConfig)) {
	require $kalpataruLocalConfig;
}

// --- database (hPanel: Databases > MySQL Databases) ----------------------
defined('DB_HOST') || define('DB_HOST', 'localhost');
defined('DB_NAME') || define('DB_NAME', 'CHANGE_ME_dbname');
defined('DB_USER') || define('DB_USER', 'CHANGE_ME_dbuser');
defined('DB_PASS') || define('DB_PASS', 'CHANGE_ME_dbpassword');

// --- admin login ----------------------------------------------------------
// Generate a hash for your chosen password with:  php tools/hash.php "your-password"
// (empty hash = login disabled until set)
defined('ADMIN_USER') || define('ADMIN_USER', 'admin');
defined('ADMIN_PASSWORD_HASH') || define('ADMIN_PASSWORD_HASH', '');

// --- lead notification email -----------------------------------------------
// Every enquiry is emailed here as well as being stored in the database.
defined('NOTIFY_EMAIL') || define('NOTIFY_EMAIL', 'CHANGE_ME@kalpataruexhibition.com');
defined('NOTIFY_FROM') || define('NOTIFY_FROM', 'website@kalpataruexhibition.com');

// --- anti-spam -------------------------------------------------------------
// Max submissions per visitor (IP) within the window.
defined('RATE_LIMIT_WINDOW_SECONDS') || define('RATE_LIMIT_WINDOW_SECONDS', 900); // 15 minutes
defined('RATE_LIMIT_MAX_SUBMISSIONS') || define('RATE_LIMIT_MAX_SUBMISSIONS', 3);
// Salt for one-way IP hashing (any random string — change it once, keep it secret).
defined('IP_SALT') || define('IP_SALT', 'kalpataru-salt-CHANGE-ME-to-a-random-string');

// --- blog -------------------------------------------------------------------
// Where admin cover uploads are stored (public folder, web-served as /blog-images/).
defined('BLOG_UPLOAD_DIR') || define('BLOG_UPLOAD_DIR', dirname(__DIR__) . '/blog-images');

// --- misc ------------------------------------------------------------------
defined('SITE_URL') || define('SITE_URL', 'https://kalpataruexhibition.com');
