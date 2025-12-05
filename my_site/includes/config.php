<?php

// This config file is basically the “central brain” for anything related to sessions
// and login attempts. I keep all of this here so I don’t have to repeat the same
// session / JSON file setup logic in every page that needs authentication.

/**
 * I’m explicitly choosing a custom directory for PHP sessions instead of the default.
 * Reason:
 * - On a shared server (like Osiris), the default session directory can be shared
 *   by many users, and I prefer to keep my stuff grouped under my project folder.
 * - It also makes debugging easier because I can inspect session files if needed.
 *
 * I only override the session save path if the directory actually exists AND
 * is writable, so I don’t accidentally break session handling in case of a bad path.
 */
$customSessionDir = __DIR__ . '/../sessions';
if (is_dir($customSessionDir) && is_writable($customSessionDir)) {
  ini_set('session.save_path', $customSessionDir);
}

/**
 * I centralize session_start() here so any file that includes config.php
 * can safely rely on $_SESSION being available.
 *
 * The session_status() check prevents PHP from complaining if a page calls
 * config.php after a session was already started somewhere else.
 */
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

/**
 * Simple helper that tells the rest of the app whether the user is logged in.
 *
 * I use a dedicated function instead of checking $_SESSION['is_logged_in']
 * everywhere because:
 * - it keeps the check consistent,
 * - if I ever change how login is stored (e.g., store a username instead),
 *   I only need to update this function.
 */
function is_logged_in(): bool {
  return !empty($_SESSION['is_logged_in']);
}

/**
 * Returns the full path to the JSON file where I track login attempts.
 *
 * I keep this in a /data folder so it’s clearly separated from code
 * and from blog_data.json. This also makes it easy to delete/inspect
 * lockout info without touching the rest of the project.
 */
function attempts_file(): string {
  return __DIR__ . '/../data/login_attempts.json';
}

/**
 * Load the current login attempt data from the JSON file.
 *
 * I use this to implement a simple lockout system:
 * - If the file doesn’t exist yet, I return an empty array, meaning
 *   nobody has any failed attempts stored.
 * - If the JSON is somehow corrupted, I fall back to an empty array
 *   instead of breaking the login page.
 *
 * @return array<string,array<string,int>>  Map username => ['attempts'=>..., 'locked_until'=>...]
 */
function load_attempts(): array {
  $file = attempts_file();
  if (!file_exists($file)) return [];

  $json = file_get_contents($file);
  $data = json_decode($json ?? '[]', true);

  // Same idea as with the blog helpers: if json_decode fails or returns something weird,
  // I just treat it as “no attempts” instead of crashing the app.
  return is_array($data) ? $data : [];
}

/**
 * Save the updated login attempt data to the JSON file.
 *
 * This is called every time someone fails or succeeds at logging in:
 * - On failure, I increment the counter and potentially set a lockout timestamp.
 * - On success, I reset their attempt count.
 *
 * I also make sure the /data directory exists before writing, so I don’t have
 * to repeat that directory creation logic inside the login page itself.
 *
 * @param array<string,array<string,int>> $data  Updated attempts data keyed by username.
 */
function save_attempts(array $data): void {
  $file = attempts_file();
  
  // Make sure the directory exists in case this is the first time I ever save attempts.
  $dir = dirname($file);
  if (!is_dir($dir)) {
    @mkdir($dir, 0775, true);
  }

  // Pretty-printing the JSON makes it easier to inspect lockout info if I need to debug.
  file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}
