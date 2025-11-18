<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$baseUrl = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/').'/';
define('BASE_URL', $baseUrl);


define('DATA_DIR', __DIR__ . '/../data');
define('ATTEMPTS_FILE', DATA_DIR . '/login_attempts.json');


if (!file_exists(ATTEMPTS_FILE)) {
  @file_put_contents(ATTEMPTS_FILE, "{}");
}


function h($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
