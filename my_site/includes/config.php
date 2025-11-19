<?php

$customSessionDir = __DIR__ . '/../sessions';
if (is_dir($customSessionDir) && is_writable($customSessionDir)) {
  ini_set('session.save_path', $customSessionDir);
}


if (session_status() === PHP_SESSION_NONE) {
  session_start();
}


function is_logged_in(): bool {
  return !empty($_SESSION['is_logged_in']);
}


function attempts_file(): string {
  return __DIR__ . '/../data/login_attempts.json';
}


function load_attempts(): array {
  $file = attempts_file();
  if (!file_exists($file)) return [];
  $json = file_get_contents($file);
  $data = json_decode($json ?? '[]', true);
  return is_array($data) ? $data : [];
}


function save_attempts(array $data): void {
  $file = attempts_file();
  
  $dir = dirname($file);
  if (!is_dir($dir)) {
    @mkdir($dir, 0775, true);
  }
  file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}
