<?php
// includes/config.php
// Safe session path (optional): use ./sessions if it exists; else, keep PHP default.
$customSessionDir = __DIR__ . '/../sessions';
if (is_dir($customSessionDir) && is_writable($customSessionDir)) {
  ini_set('session.save_path', $customSessionDir);
}

// Start session once (other files will NOT call session_start unless needed)
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// A tiny helper to know if logged in
function is_logged_in(): bool {
  return !empty($_SESSION['is_logged_in']);
}

// Path to lockout file (Lab 9 Part 4)
function attempts_file(): string {
  return __DIR__ . '/../data/login_attempts.json';
}

// Load attempts JSON (safe)
function load_attempts(): array {
  $file = attempts_file();
  if (!file_exists($file)) return [];
  $json = file_get_contents($file);
  $data = json_decode($json ?? '[]', true);
  return is_array($data) ? $data : [];
}

// Save attempts JSON (safe)
function save_attempts(array $data): void {
  $file = attempts_file();
  // Ensure /data exists
  $dir = dirname($file);
  if (!is_dir($dir)) {
    @mkdir($dir, 0775, true);
  }
  file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}
