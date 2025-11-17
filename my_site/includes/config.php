<?php
// --- Show errors while you finish Lab 9 (remove before final turn-in)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// --- Sessions
// Use the default Osiris session path (works out of the box).
// If you REALLY want your own folder, uncomment next 2 lines and ensure perms:
// $sessionsPath = __DIR__ . '/../sessions';
// ini_set('session.save_path', $sessionsPath);

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// --- BASE_URL for linking CSS/JS/images without hardcoding host
// This produces "/home/sgrondin/" on Osiris.
$baseUrl = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/').'/';
define('BASE_URL', $baseUrl);

// --- Data paths (login attempts)
define('DATA_DIR', __DIR__ . '/../data');
define('ATTEMPTS_FILE', DATA_DIR . '/login_attempts.json');

// Ensure the JSON file exists
if (!file_exists(ATTEMPTS_FILE)) {
  @file_put_contents(ATTEMPTS_FILE, "{}");
}

// --- Small helper
function h($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
