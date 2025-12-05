<?php
// This page is the main "view" for my to-do list feature. I keep the actual
// login logic and session handling in config.php/login.php so this file can
// focus on showing the list UI to someone who is already logged in.
require_once __DIR__ . '/includes/config.php';

// Before showing any tasks, I make sure the user is logged in.
// If not, I send them back to login.php so the to-do page isn’t exposed.
if (!is_logged_in()) {
  header('Location: login.php');
  exit;
}

// I try to greet the user by name. If there is a username in the session,
// I use that; otherwise I fall back to the cookie, and finally to "friend".
$u = $_SESSION['username'] ?? ($_COOKIE['todo-username'] ?? 'friend');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <!-- I personalize the page title with the username to make the to-do list feel more “owned”. -->
  <title><?= htmlspecialchars($u, ENT_QUOTES, 'UTF-8') ?>’s To-Do List</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Shared site styling (layout, typography, cards, nav, etc.). -->
  <link rel="stylesheet" href="my_style.css">
  <!-- I include Font Awesome here so I can use icons in the to-do list (e.g., trash icon in todo.js). -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
<?php
// I reuse the same navigation bar as the rest of the site so this page
// feels consistent with the other lab pages.
include_once 'nav.php';
?>

<main class="body_wrapper" style="position:relative;">
  <?php
  // This logout form is kept on the to-do page itself so the user has a quick
  // way to sign out from here without going back to login.php first.
  // I absolutely position it in the top-right to keep it out of the main flow.
  ?>
  <form method="post" action="login.php" style="position:absolute; right:0; top:-8px;">
    <button type="submit" name="logout" value="1">Log out</button>
  </form>

  <!-- I greet the user again in the main content to make the page feel more personal. -->
  <h1>Welcome back, <?= htmlspecialchars($u, ENT_QUOTES, 'UTF-8') ?>!</h1>

  <?php
  // This form is the “controller” side for the front-end: instead of submitting
  // to a PHP page, it calls addItem(event) in todo.js on submit.
  // That keeps the to-do list logic in JavaScript and avoids reloading the page.
  ?>
  <form id="todo-form" onsubmit="addItem(event)">
    <label class="todo-label" for="todo-text">Add task</label>
    <div class="todo-input-row">
      <!-- I leave the name attribute off here because I’m not posting to the server;
           the JavaScript reads the value directly by id and manages the list client-side. -->
      <input type="text" id="todo-text" placeholder="ex: finish lab 9">
      <button type="submit">Add to list</button>
    </div>
  </form>

  <?php
  // This unordered list is the container that todo.js fills with <li> elements.
  // Keeping it empty in the HTML makes it clear that all tasks are rendered dynamically.
  ?>
  <ul id="todo-list" class="todo-list"></ul>
</main>

<?php
// Standard footer shared with the rest of the site.
include_once 'footer.php';
?>

<!-- I load the to-do JavaScript at the end so the DOM is ready when the script
     attaches event listeners and starts building list items. -->
<script src="todo.js"></script>
</body>
</html>
