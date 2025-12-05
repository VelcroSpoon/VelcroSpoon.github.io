<?php
// This file builds the main navigation bar that appears on every page.
// It also handles the “current page” highlight and shows a small greeting
// when the user is logged into the to-do section.

// I keep track of the username either from the active session or,
// if that’s not set yet on this request, from the long-lived cookie.
$u = $_SESSION['username'] ?? ($_COOKIE['todo-username'] ?? null);

// Here I figure out which PHP file is currently being loaded
// (e.g. "index.php", "blog.php") so I can style that nav link differently.
$current = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '');

// Small helper to add the "current_page" CSS class when a link
// matches the current PHP file. This keeps the HTML markup cleaner.
function active($file) {
  global $current;
  return $current === $file ? ' class="current_page"' : '';
}
?>
<nav class="site-nav">
  <!-- The logo always links back to the homepage, and I add an aria-label
       so screen readers know this is a “Home” link. -->
  <a href="index.php" class="nav-logo" aria-label="Home">
    <img src="images/bird.png" alt="Simon" />
  </a>

  <!-- This wrapper holds all the main navigation links and the dropdown.
       It also doubles as the collapsible area controlled by the hamburger on mobile. -->
  <div class="nav-links" id="navmenu">
    <!-- Each link calls active(...) so the CSS can highlight the current page. -->
    <a<?=active('index.php')?> href="index.php">Home</a>

    <!-- I group the two “personal” pages in a dropdown to avoid cluttering
         the nav bar with too many top-level links. -->
    <div class="dropdown">
      <button type="button" class="dropbtn">Discover me ▾</button>
      <div class="dropdown-content">
        <a<?=active('my_artistic_self.php')?> href="my_artistic_self.php">My artistic self</a>
        <a<?=active('my_vacation.php')?> href="my_vacation.php">My dream vacation</a>
      </div>
    </div>

    <!-- These links point to the interactive lab pages (marketplace, quiz, blog, to-do). -->
    <a<?=active('marketplace.php')?> href="marketplace.php">Marketplace</a>
    <a<?=active('my_form.php')?> href="my_form.php">My quiz</a>
    <!-- New blog link for Lab 10 so the blog behaves like a first-class page in the nav. -->
    <a<?=active('blog.php')?> href="blog.php">Blog</a>
    <a<?=active('login.php')?> href="login.php">To-Do (login)</a>
  </div>

  <?php if ($u): ?>
    <!-- If I know who’s logged in, I show a small “Hello, username” message
         on the right side of the nav. I escape it for safety in case the
         username contains special characters. -->
    <span style="margin-left:auto;opacity:.85;padding:.25rem .5rem;">
      Hello, <?= htmlspecialchars($u, ENT_QUOTES, 'UTF-8') ?>!
    </span>
  <?php endif; ?>

  <!-- This button is only styled/visible on smaller screens.
       The JavaScript in nav.js toggles the nav-links open/closed using
       aria-expanded to keep it accessible. -->
  <button id="hamburger" class="hamburger" aria-expanded="false" aria-controls="navmenu">☰</button>
</nav>

<!-- I keep all navigation JavaScript (hamburger + mobile dropdown behavior)
     in a separate file so the PHP/HTML here stays focused on structure. -->
<script src="nav.js" defer></script>
