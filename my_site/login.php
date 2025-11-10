<?php
const PW_SHA256 = 'b14e9015dae06b5e206c2b37178eac45e193792c5ccf1d48974552614c61f2ff'; // sha256("CS203")

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $pass = $_POST['password'] ?? '';
  if (hash_equals(PW_SHA256, hash('sha256', $pass))) {

    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $base   = rtrim($scheme.'://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['REQUEST_URI']), '/').'/';
    header('Location: '.$base.'to-do.php');
    exit;
  } else {
    $err = 'Wrong password. Please try again.';
  }
}
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
  </style>
</head>
<body>
  <?php include_once 'nav.php'; ?>

  <main class="body_wrapper">
    <div class="login-card">
      <h1>Enter password to access your To-Do</h1>
      <form action="login.php" method="post">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Unlock</button>
      </form>
      <?php if ($err): ?>
        <div class="err"><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>
    </div>
  </main>

  <?php include_once 'footer.php'; ?>
</body>
</html>
