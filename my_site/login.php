<?php

// This page is the login “controller” for my small to-do app. It checks the
// username/password, applies a simple lockout after too many wrong attempts,
// and either logs the user in or shows them the form with error messages.

require_once __DIR__ . '/includes/config.php';

// I keep a couple of small pieces of state here:
// - $err: any error message I want to show above the form
// - $info: success / info messages like “Successfully logged out.”
$err = '';
$info = '';
// I prefill the username field with a cookie so returning users don’t have to
// retype their name every time.
$username = $_COOKIE['todo-username'] ?? '';
// This controls how long a user is locked out after 3 wrong passwords.
$LOCK_SECONDS = 30;
// I only store a hash of the real password instead of the plain text. This
// lets me compare passwords securely and still keep the actual value hidden.
$REQUIRED_HASH = 'b14e9015dae06b5e206c2b37178eac45e193792c5ccf1d48974552614c61f2ff'; // sha256("CS203")

// If the form is submitted with a "logout" button, I treat this as a request
// to end the current session and show a friendly confirmation message.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
  session_destroy();
  session_start();
  $info = 'Successfully logged out.';
}

// If someone somehow reaches the login page while already logged in, I send
// them straight to the to-do list instead of letting them “log in again”.
if (is_logged_in()) {
  header('Location: to-do.php');
  exit;
}

// Handle login submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
  // I trim the username to avoid issues where leading/trailing spaces could
  // accidentally create different “users”.
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';

  // Basic guard: both fields have to be filled before I even touch the hash.
  if ($username === '' || $password === '') {
    $err = 'Please enter both username and password.';
  } else {

    // I keep track of login attempts in a small JSON file so that lockouts
    // still work even if the server restarts.
    $attempts = load_attempts();
    if (!isset($attempts[$username])) {
      // First time we see this username, we start its counter at 0.
      $attempts[$username] = ['attempts'=>0, 'locked_until'=>0];
    }

    // If this user is currently locked, I show them how many seconds are left
    // instead of letting them keep trying.
    if ($attempts[$username]['locked_until'] > time()) {
      $remaining = $attempts[$username]['locked_until'] - time();
      $err = 'Too many wrong tries. Please wait ' . $remaining . 's and try again.';
    } else {
      // Here I compare the hash of the submitted password with my stored hash.
      // That way I never store or echo the real password anywhere.
      $ok = hash('sha256', $password) === $REQUIRED_HASH;

      if ($ok) {
        // On success, I reset their attempt counter so future logins start fresh.
        $attempts[$username] = ['attempts'=>0, 'locked_until'=>0];
        save_attempts($attempts);

        // I remember the username in a cookie for 30 days so the field can be
        // prefilled next time they come back.
        setcookie('todo-username', $username, time()+60*60*24*30, '/'); // 30 days

        // These session values are what other pages use to check if the user
        // is currently logged in and who they are.
        $_SESSION['is_logged_in'] = true;
        $_SESSION['username'] = $username;

        // Once everything is set up, I send them to the to-do app.
        header('Location: to-do.php');
        exit;
      } else {
        // Wrong password: I increase the counter and either show how many
        // tries are used or lock them out if they hit 3 in a row.
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
  <!-- Shared CSS for layout, nav, and form styling -->
  <link rel="stylesheet" href="my_style.css">
</head>
<body>
<?php
// I include the same navigation bar here so users can move between pages
// consistently, even while they’re on the login screen.
include_once 'nav.php';
?>
<main class="body_wrapper">
  <h1>Login</h1>

  <?php if ($info): ?>
    <!-- This block only appears when I have an informational message
         (for example after a successful logout). -->
    <p class="notice"><?= htmlspecialchars($info, ENT_QUOTES, 'UTF-8') ?></p>
  <?php endif; ?>

  <?php if ($err): ?>
    <!-- This block shows validation / login errors, like missing fields,
         wrong password, or being locked out. -->
    <p class="error"><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></p>
  <?php endif; ?>

  <!-- Simple login form: I send everything back to the same page (login.php)
       so the PHP above can validate and either log the user in or show errors. -->
  <form method="post" action="login.php" style="max-width:520px;">
    <p>
      <label for="username">Username</label><br>
      <input
        type="text"
        id="username"
        name="username"
        value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>"
        required
      >
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
<?php
// Footer is also reused here for consistency with the rest of the site.
include_once 'footer.php';
?>
</body>
</html>
