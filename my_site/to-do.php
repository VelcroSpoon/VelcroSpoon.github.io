<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

if (empty($_SESSION['is_logged_in'])) {
  header('Location: login.php');
  exit;
}
$username = $_SESSION['username'] ?? ($_COOKIE['todo-username'] ?? 'Friend');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>To-Do List</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="my_style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
  <?php include_once __DIR__ . '/nav.php'; ?>

  <main class="body_wrapper">
    <h1><?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>’s To-Do List</h1>

    <!-- logout form (posts back to login.php to destroy session) -->
    <form method="post" action="login.php" style="position:relative;">
      <button type="submit" name="logout" value="1"
        style="position:absolute;right:0;top:-2.2rem;padding:.35rem .7rem;border:none;border-radius:.5rem;background:#333;color:#fff;cursor:pointer;">
        Log out
      </button>
    </form>

    <p>Write something and click "Add to list". It will stay even if you refresh.</p>

    <form id="todo-form" onsubmit="addItem(event)">
      <input type="text" id="todo-text" placeholder="ex: finish lab 9">
      <button type="submit">Add to list</button>
    </form>

    <ul id="todo-list"></ul>
  </main>

  <?php include_once __DIR__ . '/footer.php'; ?>
  <script src="todo.js"></script>
</body>
</html>
