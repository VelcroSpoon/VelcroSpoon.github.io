<?php
require_once __DIR__.'/includes/config.php';
session_start();

// Gate: must be logged in
if (empty($_SESSION['is_logged_in'])) {
  header('Location: ' . base_url() . 'login.php');
  exit;
}

// Prefer session username; fall back to cookie
$username = $_SESSION['username'] ?? ($_COOKIE['todo-username'] ?? 'Student');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>'s To-Do</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="my_style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <style>
    .logout-wrap{ position: relative; }
    .logout-wrap form{ position: absolute; top: -8px; right: 0; }
    .logout-wrap button{ padding:6px 10px; border:none; border-radius:8px; background:#333; color:#fff; cursor:pointer; }
  </style>
</head>
<body>
  <?php include_once 'nav.php'; ?>

  <main class="body_wrapper">
    <div class="logout-wrap">
      <h1>Welcome back, <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>!</h1>
      <form action="login.php" method="post">
        <input type="hidden" name="logout" value="1">
        <button type="submit">Log out</button>
      </form>
    </div>

    <p>Write something and click "Add to list". It stays even if you refresh.</p>

    <form id="todo-form" onsubmit="addItem(event)">
      <label class="todo-label" for="todo-text">New item</label>
      <div class="todo-input-row">
        <input type="text" id="todo-text" placeholder="ex: finish lab 9">
        <button type="submit">Add to list</button>
      </div>
    </form>

    <ul id="todo-list" class="todo-list"></ul>
  </main>

  <?php include_once 'footer.php'; ?>
  <script src="todo.js"></script>
</body>
</html>
