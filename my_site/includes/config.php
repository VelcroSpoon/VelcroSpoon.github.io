<?php
// includes/config.php

// On Osiris, do NOT override session.save_path (per prof note). On localhost you can.
// We'll detect localhost and only then redirect sessions to ./sessions/.
if (!empty($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] === 'localhost') {
  // Optional: if you made a local "sessions" folder for XAMPP
  // ini_set('session.save_path', __DIR__ . '/../sessions/');
}

// A small helper to build absolute URLs for redirects
function base_url(): string {
  $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
  $dir = rtrim(dirname($_SERVER['REQUEST_URI']), '/\\');
  return $scheme.'://'.$_SERVER['HTTP_HOST'].($dir ? $dir.'/' : '/');
}
