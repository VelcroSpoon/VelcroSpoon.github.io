<?php
if (!empty($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] === 'localhost') {
  
}

function base_url(): string {
  $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
  $dir = rtrim(dirname($_SERVER['REQUEST_URI']), '/\\');
  return $scheme.'://'.$_SERVER['HTTP_HOST'].($dir ? $dir.'/' : '/');
}
