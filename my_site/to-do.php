<?php
// to-do.php
require_once __DIR__ . '/includes/config.php';

// Gate: must be logged in
if (empty($_SESSION['is_logged_in']) || empty($_SESSION['username'])) {
  redirect('login.php');
}

$username = $_SESSION['username'];

// Handle logout POST (can also be done in login.php — both acceptable)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout']) && $_POST['logout'] === '1') {
  session_destroy();
  session_start();
  redirect('login.php');
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($username) ?>’s To-Do List</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?= BASE_URL ?>my_style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <style>
    /* Simple top-right logout */
    .logout-wrap{ position:relative; }
    .logout-wrap form{ position:absolute; right:0; top:-8px; }
  </style>
</head>
<body>
  <?php include __DIR__ . '/nav.php'; ?>

  <main class="body_wrapper logout-wrap">
    <form method="post" action="">
      <input type="hidden" name="logout" value="1">
      <button type="submit">Log out</button>
    </form>

    <h1>Welcome back, <?= htmlspecialchars($username) ?>!</h1>
    <p>This is your personal to-do list. Items persist in your browser via localStorage.</p>

    <form id="todo-form" onsubmit="addItem(event)">
      <label class="todo-label" for="todo-text">New item</label>
      <div class="todo-input-row">
        <input type="text" id="todo-text" placeholder="ex: finish lab 9" required>
        <button type="submit">Add to list</button>
      </div>
    </form>

    <ul id="todo-list" class="todo-list"></ul>
  </main>

  <?php include __DIR__ . '/footer.php'; ?>
  <script src="<?= BASE_URL ?>todo.js"></script>
</body>
</html>
