<?php
// ===== MODEL (top) =====
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

$err = '';
$justLoggedOut = false;

// Handle logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
  session_destroy();
  // restart empty session to render page safely
  session_start();
  $justLoggedOut = true;
}

// If already logged in, go directly to to-do
if (!isset($_POST['logout']) && !empty($_SESSION['is_logged_in'])) {
  header('Location: to-do.php');
  exit;
}

// Cached username from cookie
$savedName = $_COOKIE['todo-username'] ?? '';

// Handle login submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {
  $username = trim($_POST['username']);
  $password = (string)$_POST['password'];

  if ($username === '' || $password === '') {
    $err = 'Please enter both username and password.';
  } else {
    // Hash check (no plain "CS203" in the code)
    $okHash = 'b14e9015dae06b5e206c2b37178eac45e193792c5ccf1d48974552614c61f2ff'; // hash('sha256', 'CS203')
    if (hash('sha256', $password) === $okHash) {
      $_SESSION['is_logged_in'] = true;
      $_SESSION['username'] = $username;
      setcookie('todo-username', $username, time() + 86400*30, '/'); // 30 days
      header('Location: to-do.php');
      exit;
    } else {
      $err = 'Wrong password.';
    }
  }
}
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
  <?php include_once __DIR__ . '/nav.php'; ?>

  <main class="body_wrapper">
    <h1>Login</h1>

    <?php if ($justLoggedOut): ?>
      <p style="color:#065f46;background:#ecfdf5;border:1px solid #a7f3d0;padding:.5rem .75rem;border-radius:.5rem;">
        Successfully logged out.
      </p>
    <?php endif; ?>

    <?php if ($err !== ''): ?>
      <p style="color:#b91c1c;background:#fef2f2;border:1px solid #fecaca;padding:.5rem .75rem;border-radius:.5rem;">
        <?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?>
      </p>
    <?php endif; ?>

    <form method="post" action="login.php" style="max-width:520px;">
      <p>
        <label for="username">Username</label><br>
        <input type="text" id="username" name="username"
               value="<?= htmlspecialchars($savedName, ENT_QUOTES, 'UTF-8') ?>"
               required>
      </p>
      <p>
        <label for="password">Password</label><br>
        <input type="password" id="password" name="password" required>
      </p>
      <p><button type="submit">Login</button></p>
    </form>
  </main>

  <?php include_once __DIR__ . '/footer.php'; ?>
</body>
</html>
