<?php
require_once __DIR__ . '/includes/config.php';

if (!is_logged_in()) {
  header('Location: login.php');
  exit;
}
$u = $_SESSION['username'] ?? ($_COOKIE['todo-username'] ?? 'friend');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($u) ?>’s To-Do List</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="my_style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
<?php include_once 'nav.php'; ?>

<main class="body_wrapper" style="position:relative;">
  <form method="post" action="login.php" style="position:absolute; right:0; top:-8px;">
    <button type="submit" name="logout" value="1">Log out</button>
  </form>

  <h1>Welcome back, <?= htmlspecialchars($u) ?>!</h1>
  <p>Your tasks persist in this browser using localStorage.</p>

  <form id="todo-form" onsubmit="addItem(event)">
    <label class="todo-label" for="todo-text">Add task</label>
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
