<?php

require_once __DIR__ . '/includes/config.php';


$err = '';
$info = '';
$username = $_COOKIE['todo-username'] ?? '';
$LOCK_SECONDS = 30;
$REQUIRED_HASH = 'b14e9015dae06b5e206c2b37178eac45e193792c5ccf1d48974552614c61f2ff'; // sha256("CS203")


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
  session_destroy();
  session_start();
  $info = 'Successfully logged out.';
}

if (is_logged_in()) {
  header('Location: to-do.php');
  exit;
}

// Handle login submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';

  if ($username === '' || $password === '') {
    $err = 'Please enter both username and password.';
  } else {
    
    $attempts = load_attempts();
    if (!isset($attempts[$username])) {
      $attempts[$username] = ['attempts'=>0, 'locked_until'=>0];
    }

    if ($attempts[$username]['locked_until'] > time()) {
      $remaining = $attempts[$username]['locked_until'] - time();
      $err = 'Too many wrong tries. Please wait ' . $remaining . 's and try again.';
    } else {
      // Check password using hash
      $ok = hash('sha256', $password) === $REQUIRED_HASH;

      if ($ok) {
        
        $attempts[$username] = ['attempts'=>0, 'locked_until'=>0];
        save_attempts($attempts);

        setcookie('todo-username', $username, time()+60*60*24*30, '/'); // 30 days
        $_SESSION['is_logged_in'] = true;
        $_SESSION['username'] = $username;
        header('Location: to-do.php');
        exit;
      } else {
       
        $attempts[$username]['attempts'] += 1;
        if ($attempts[$username]['attempts'] >= 3) {
          $attempts[$username]['locked_until'] = time() + $LOCK_SECONDS;
          $attempts[$username]['attempts'] = 0;
          $err = 'Wrong password 3 times. You are locked out for '.$LOCK_SECONDS.' seconds.';
        } else {
          $err = 'Wrong password. Tries: ' . $attempts[$username]['attempts'] . '/3';
        }
        save_attempts($attempts);
      }
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
<?php include_once 'nav.php'; ?>
<main class="body_wrapper">
  <h1>Login</h1>

  <?php if ($info): ?>
    <p class="notice"><?= htmlspecialchars($info, ENT_QUOTES, 'UTF-8') ?></p>
  <?php endif; ?>
  <?php if ($err): ?>
    <p class="error"><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></p>
  <?php endif; ?>

  <form method="post" action="login.php" style="max-width:520px;">
    <p>
      <label for="username">Username</label><br>
      <input type="text" id="username" name="username"
             value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>" required>
    </p>
    <p>
      <label for="password">Password</label><br>
      <input type="password" id="password" name="password" required>
    </p>
    <p>
      <button type="submit" name="login" value="1">Login</button>
    </p>
  </form>
</main>
<?php include_once 'footer.php'; ?>
</body>
</html>
