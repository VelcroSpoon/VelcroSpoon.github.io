<?php
// to-do.php — gate with session
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['is_logged_in'])) {
  header('Location: login.php');
  exit;
}
$user = $_SESSION['username'] ?? ($_COOKIE['todo-username'] ?? 'friend');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>To-Do List</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="my_style.css">
</head>
<body>
  <?php include_once 'nav.php'; ?>

  <main class="body_wrapper">
    <form method="post" action="login.php" style="position:relative;">
      <input type="hidden" name="logout" value="1">
      <button type="submit" style="position:absolute; right:0; top:-8px;">Log out</button>
    </form>

    <h1><?= htmlspecialchars($user, ENT_QUOTES, 'UTF-8') ?>’s To-Do List</h1>
    <p>Write something and click “Add to list”. It will stay even if you refresh.</p>

    <form id="todo-form" onsubmit="addItem(event)">
      <input type="text" id="todo-text" placeholder="ex: finish lab 9">
      <button type="submit">Add to list</button>
    </form>

    <ul id="todo-list" class="todo-list"></ul>
  </main>

  <?php include_once 'footer.php'; ?>
  <script src="todo.js"></script>
</body>
</html>
