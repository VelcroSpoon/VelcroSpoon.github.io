<?php
// login.php  — MODEL (top): sessions, handle login/logout, prefill username
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$err = '';
// Already authenticated? go to to-do
if (!empty($_SESSION['is_logged_in'])) {
  header('Location: to-do.php');
  exit;
}

// Logout intent arrives here (from to-do.php)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
  session_destroy();
  // restart a clean session so the page can render without warnings
  session_start();
  $err = 'Successfully logged out.';
}

// Handle login attempt
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {
  $u = trim($_POST['username']);
  $p = $_POST['password'];

  if ($u === '' || $p === '') {
    $err = 'Please enter both username and password.';
  } elseif ($p !== 'CS203') {
    $err = 'Wrong password. Hint for testing: use CS203.';
  } else {
    // Success
    $_SESSION['is_logged_in'] = true;
    $_SESSION['username'] = $u;
    // cookie for greeting
    setcookie('todo-username', $u, time()+60*60*24*30, '/');
    header('Location: to-do.php');
    exit;
  }
}

// Prefill username from cookie
$prefill = $_COOKIE['todo-username'] ?? '';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="my_style.css">
</head>
<body>
  <?php include_once 'nav.php'; ?>

  <main class="body_wrapper">
    <h1>Login</h1>

    <?php if ($err): ?>
      <p style="color:#b91c1c;font-weight:600;"><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></p>
    <?php else: ?>
      <p style="opacity:.8">Use any username and password <code>CS203</code> to enter.</p>
    <?php endif; ?>

    <form method="post" action="login.php" style="max-width:520px;">
      <p>
        <label for="username">Username</label><br>
        <input id="username" name="username" type="text" required value="<?= htmlspecialchars($prefill, ENT_QUOTES, 'UTF-8') ?>">
      </p>
      <p>
        <label for="password">Password</label><br>
        <input id="password" name="password" type="password" required>
      </p>
      <p><button type="submit">Login</button></p>
    </form>
  </main>

  <?php include_once 'footer.php'; ?>
</body>
</html>
