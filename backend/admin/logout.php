<?php
/** End the admin session. */
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/helpers.php';

session_start_secure();
logout();
header('Location: /admin/login.php');
exit;
