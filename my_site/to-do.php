<?php
// require cookie-auth
if (!isset($_COOKIE['auth']) || $_COOKIE['auth'] !== 'ok') {
  header('Location: /home/sgrondin/login.php');
  exit;
}
$username = $_COOKIE['todo-username'] ?? 'friend';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>’s To-Do</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/home/sgrondin/my_style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
  <?php include_once __DIR__ . '/nav.php'; ?>

  <main class="body_wrapper">
    <form method="post" action="/home/sgrondin/login.php" style="position:relative;">
      <input type="hidden" name="logout" value="1">
      <button type="submit" style="position:absolute;right:0;top:-10px;">Log out</button>
    </form>

    <h1>Welcome back, <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>!</h1>
    <p>Write something and click “Add to list”. Items are saved in your browser (localStorage).</p>

    <form id="todo-form" onsubmit="addItem(event)">
      <label class="todo-label" for="todo-text">New item</label>
      <div class="todo-input-row">
        <input type="text" id="todo-text" placeholder="ex: finish lab 9">
        <button type="submit">Add to list</button>
      </div>
    </form>

    <ul id="todo-list" class="todo-list"></ul>
  </main>

  <?php include_once __DIR__ . '/footer.php'; ?>
  <script src="/home/sgrondin/todo.js"></script>
</body>
</html>
