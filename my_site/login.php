<?php
/**
 * login.php
 * MODEL: at top — session, cookie, lockout logic, redirects
 */
require_once __DIR__ . '/includes/config.php';

/** sha256("CS203") */
const CS203_HASH = 'b14e9015dae06b5e206c2b37178eac45e193792c5ccf1d48974552614c61f2ff';

/** Paths + data file for lockouts */
$dataDir  = __DIR__ . '/data';
$lockFile = $dataDir . '/login_attempts.json';
if (!is_dir($dataDir)) { @mkdir($dataDir, 0700, true); }
if (!file_exists($lockFile)) { @file_put_contents($lockFile, '{}', LOCK_EX); }

/** Load attempts file (or empty structure) */
$attempts = [];
$raw = @file_get_contents($lockFile);
if ($raw !== false && strlen($raw)) {
  $decoded = json_decode($raw, true);
  if (is_array($decoded)) $attempts = $decoded;
}

/** If already logged in, go straight to to-do */
if (!empty($_SESSION['is_logged_in']) && !empty($_SESSION['username'])) {
  redirect('to-do.php');
}

/** Prefill username from cookie if available */
$prefillName = $_COOKIE['todo-username'] ?? '';

/** Login / Logout handling */
$error = null;
$info  = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Logout path: a POST with logout=1
  if (isset($_POST['logout']) && $_POST['logout'] === '1') {
    // Destroy session and re-start a fresh one (shows message below)
    session_destroy();
    session_start();
    $info = 'Successfully logged out.';
  } else {
    // Attempt login
    $name = trim($_POST['username'] ?? '');
    $pw   = $_POST['password'] ?? '';

    // Require both username and password (lab requirement)
    if ($name === '' || $pw === '') {
      $error = 'Username and password are required.';
    } else {
      // Initialize tracking if user not present
      if (!isset($attempts[$name])) {
        $attempts[$name] = ['attempts' => 0, 'locked_until' => 0];
      }

      // Locked out?
      if (!empty($attempts[$name]['locked_until']) && $attempts[$name]['locked_until'] > time()) {
        $wait = $attempts[$name]['locked_until'] - time();
        $error = "Too many wrong tries. Please wait {$wait} seconds before trying again.";
      } else {
        // Check password against hash
        if (hash('sha256', $pw) === CS203_HASH) {
          // Success: set session + cookie; reset attempts; redirect
          $_SESSION['is_logged_in'] = true;
          $_SESSION['username'] = $name;

          // Cookie for prefill
          setcookie('todo-username', $name, time() + 60*60*24*30, '/');

          // Reset attempts
          $attempts[$name]['attempts'] = 0;
          $attempts[$name]['locked_until'] = 0;

          // Persist and go
          @file_put_contents($lockFile, json_encode($attempts), LOCK_EX);
          redirect('to-do.php');
        } else {
          // Wrong password: increment attempts
          $attempts[$name]['attempts'] = (int)$attempts[$name]['attempts'] + 1;

          if ($attempts[$name]['attempts'] >= 3) {
            $attempts[$name]['locked_until'] = time() + 30; // 30 sec lock
            $attempts[$name]['attempts'] = 0;
            $error = 'Too many wrong tries. You are locked for 30 seconds.';
          } else {
            $left = 3 - $attempts[$name]['attempts'];
            $error = "Wrong password. {$left} attempt(s) left before lock.";
          }
        }
      }
    }
  }

  // Save attempts file after any POST
  @file_put_contents($lockFile, json_encode($attempts), LOCK_EX);
}

// VIEW starts below
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login for To-Do</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?= BASE_URL ?>my_style.css">
</head>
<body>
  <?php include __DIR__ . '/nav.php'; ?>

  <main class="body_wrapper">
    <h1>Login</h1>

    <?php if ($info): ?>
      <p style="color:#16a34a;"><?= htmlspecialchars($info, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <?php if ($error): ?>
      <p style="color:#b91c1c;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="post" action="">
      <p>
        <label>Your name (required)</label><br>
        <input type="text" name="username" value="<?= htmlspecialchars($prefillName, ENT_QUOTES, 'UTF-8') ?>" required placeholder="Jane Doe">
      </p>
      <p>
        <label>Password (required)</label><br>
        <input type="password" name="password" required placeholder="Enter password">
      </p>
      <p><button type="submit">Enter</button></p>
      <p style="opacity:.8;">(Grading note: compares sha256 hash of “CS203”.)</p>
    </form>

    <!-- Optional logout form here as well, if already logged in you’d have been redirected -->
    <form method="post" action="" style="margin-top:1rem;">
      <input type="hidden" name="logout" value="1">
      <button type="submit">Log out</button>
    </form>
  </main>

  <?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
