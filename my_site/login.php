<?php
// ============ MODEL ============
$ERROR = '';
$JUST_LOGGED_OUT = false;

// Already authenticated? go to to-do
if (isset($_COOKIE['auth']) && $_COOKIE['auth'] === 'ok') {
  header('Location: /home/sgrondin/to-do.php');
  exit;
}

// Logout flow (from to-do.php button)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['logout'] ?? '') === '1') {
  setcookie('auth', '', time()-3600, '/');
  setcookie('todo-username', '', time()-3600, '/');
  $JUST_LOGGED_OUT = true;
}

// Login submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['login'] ?? '') === '1') {
  $user = trim($_POST['username'] ?? '');
  $pass = $_POST['password'] ?? '';

  if ($user === '' || $pass === '') {
    $ERROR = 'Please enter both username and password.';
  } else {
    $expectedHash = 'b14e9015dae06b5e206c2b37178eac45e193792c5ccf1d48974552614c61f2ff'; // sha256("CS203")
    $givenHash    = hash('sha256', $pass);
    if (hash_equals($expectedHash, $givenHash)) {
      setcookie('auth', 'ok', time()+2*3600, '/');                    // 2 hours
      setcookie('todo-username', $user, time()+30*24*3600, '/');      // 30 days
      header('Location: /home/sgrondin/to-do.php');
      exit;
    } else {
      $ERROR = 'Wrong password.';
    }
  }
}

$PREFILL_USER = $_COOKIE['todo-username'] ?? '';
// ============ VIEW ============
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/home/sgrondin/my_style.css">
</head>
<body>
  <?php include_once __DIR__ . '/nav.php'; ?>

  <main class="body_wrapper">
    <h1>Login to access your To-Do list</h1>

    <?php if ($JUST_LOGGED_OUT): ?>
      <p style="color:#065f46;background:#ecfdf5;border:1px solid #10b981;padding:8px 12px;border-radius:8px;max-width:600px;">
        Successfully logged out.
      </p>
    <?php endif; ?>

    <?php if ($ERROR): ?>
      <p style="color:#b91c1c;background:#fef2f2;border:1px solid #ef4444;padding:8px 12px;border-radius:8px;max-width:600px;">
        <?= htmlspecialchars($ERROR, ENT_QUOTES, 'UTF-8') ?>
      </p>
    <?php endif; ?>

    <form method="post" action="/home/sgrondin/login.php" style="max-width:520px;">
      <input type="hidden" name="login" value="1">
      <p>
        <label for="username">Username</label><br>
        <input id="username" name="username" type="text" required value="<?= htmlspecialchars($PREFILL_USER, ENT_QUOTES, 'UTF-8') ?>">
      </p>
      <p>
        <label for="password">Password</label><br>
        <input id="password" name="password" type="password" required>
      </p>
      <p><button type="submit">Login</button></p>
      <p style="opacity:.75">Hint: the password is the course code you used earlier (checked via SHA-256 hash).</p>
    </form>
  </main>

  <?php include_once __DIR__ . '/footer.php'; ?>
</body>
</html>
