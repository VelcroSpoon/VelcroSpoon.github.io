<?php
// ===== MODEL (top of file) =====
require_once __DIR__.'/includes/config.php';
session_start();

const PW_SHA256 = 'b14e9015dae06b5e206c2b37178eac45e193792c5ccf1d48974552614c61f2ff'; // sha256("CS203")

// If already logged in, go straight to to-do
if (!empty($_SESSION['is_logged_in'])) {
  header('Location: ' . base_url() . 'to-do.php');
  exit;
}

// Handle logout request (form posts back here)
$logoutMsg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
  session_destroy();
  // Start a fresh session to show the "logged out" message
  session_start();
  $logoutMsg = 'Successfully logged out.';
}

// Read saved username from cookie (for prefill)
$cookieUser = $_COOKIE['todo-username'] ?? '';

// Lockout storage: put it in system temp dir so Osiris allows writing
$lockFile = rtrim(sys_get_temp_dir(), '/\\') . '/login_attempts.json';

// Load attempts
$attempts = [];
if (is_readable($lockFile)) {
  $json = file_get_contents($lockFile);
  $attempts = json_decode($json, true);
  if (!is_array($attempts)) $attempts = [];
}

// Helper to save attempts (best-effort; ignore failure gracefully)
function save_attempts($file, $data) {
  @file_put_contents($file, json_encode($data));
}

// Handle login
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password']) && empty($_POST['logout'])) {
  $user = trim($_POST['username']);
  $pass = (string)($_POST['password']);

  if ($user === '' || $pass === '') {
    $error = 'Please enter both username and password.';
  } else {
    // Ensure user node exists
    if (!isset($attempts[$user])) {
      $attempts[$user] = ['attempts' => 0, 'locked_until' => 0];
    }

    // If locked out, block
    if ($attempts[$user]['locked_until'] > time()) {
      $remaining = $attempts[$user]['locked_until'] - time();
      $error = "Too many attempts. Try again in {$remaining} seconds.";
    } else {
      // Check password via hash
      $ok = hash_equals(PW_SHA256, hash('sha256', $pass));
      if ($ok) {
        // Success → reset attempts, set session, set cookie, redirect
        $attempts[$user]['attempts'] = 0;
        $attempts[$user]['locked_until'] = 0;
        save_attempts($lockFile, $attempts);

        $_SESSION['is_logged_in'] = true;
        $_SESSION['username'] = $user;

        // Cookie for 90 days
        setcookie('todo-username', $user, [
          'expires'  => time() + 60*60*24*90,
          'path'     => '/',
          'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
          'httponly' => false,
          'samesite' => 'Lax'
        ]);

        header('Location: ' . base_url() . 'to-do.php');
        exit;
      } else {
        // Wrong password → increment attempts
        $attempts[$user]['attempts'] += 1;
        if ($attempts[$user]['attempts'] >= 3) {
          $attempts[$user]['locked_until'] = time() + 30; // 30s lock
          $attempts[$user]['attempts'] = 0;               // reset counter
          $error = 'Too many wrong attempts. Locked for 30 seconds.';
        } else {
          $left = 3 - $attempts[$user]['attempts'];
          $error = "Wrong password. Attempts left: {$left}.";
        }
        save_attempts($lockFile, $attempts);
      }
    }
  }
}

// ===== VIEW (HTML below) =====
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login to To-Do</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="my_style.css">
  <style>
    .login-card{max-width:520px;margin:24px auto;padding:16px 18px;border:1px solid #d6deee;border-radius:12px;background:#fff;box-shadow:0 3px 12px rgba(11,18,32,0.04);}
    .login-card h1{margin:0 0 10px;}
    .login-card label{display:block;font-weight:600;margin-bottom:6px;}
    .login-card input{width:100%;padding:10px 12px;border:1px solid #cfd7e6;border-radius:10px;}
    .login-card button{margin-top:10px;padding:10px 16px;border:none;border-radius:10px;background:#0b1220;color:#fff;font-weight:700;cursor:pointer;}
    .err{color:#b91c1c;font-weight:600;margin-top:10px;}
    .ok{color:#047857;font-weight:600;margin-top:10px;}
  </style>
</head>
<body>
  <?php include_once 'nav.php'; ?>

  <main class="body_wrapper">
    <div class="login-card">
      <h1>Enter password to access your To-Do</h1>

      <?php if ($logoutMsg): ?>
        <div class="ok"><?= htmlspecialchars($logoutMsg, ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>
      <?php if ($error): ?>
        <div class="err"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>

      <form action="login.php" method="post" autocomplete="on">
        <label for="username">Username</label>
        <input type="text" id="username" name="username"
               value="<?= htmlspecialchars($cookieUser, ENT_QUOTES, 'UTF-8') ?>" required>

        <label for="password" style="margin-top:10px;">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Unlock</button>
      </form>
    </div>
  </main>

  <?php include_once 'footer.php'; ?>
</body>
</html>
