<?php
// nav.php — single source of truth for the top navigation

// Start session only if not already started (prevents the notice you saw)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// who’s logged in (from session or cookie)
$user = $_SESSION['username'] ?? ($_COOKIE['todo-username'] ?? null);

// simple helper to mark active link
$here = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
function active($file) {
    global $here;
    return $here === $file ? ' current_page' : '';
}
?>
<nav class="site-nav" role="navigation">
  <!-- Logo / Home -->
  <a href="index.php" class="nav-logo" aria-label="Home">
    <picture>
      <source srcset="images/bird.avif" type="image/avif">
      <img src="images/bird.png" alt="Site logo" />
    </picture>
    <span class="logo-text">Simon</span>
  </a>

  <!-- Hamburger (mobile) -->
  <button id="hamburger" class="hamburger" aria-controls="nav-links" aria-expanded="false">☰</button>

  <!-- Links -->
  <div id="nav-links" class="nav-links">
    <a href="index.php" class="<?= active('index.php') ?>">Home</a>

    <!-- Dropdown -->
    <div class="dropdown">
      <button id="dropbtn" class="dropbtn" aria-haspopup="true" aria-expanded="false">Discover me ▾</button>
      <div id="dropdown-content" class="dropdown-content" role="menu">
        <a href="my_artistic_self.php" class="<?= active('my_artistic_self.php') ?>">My Artistic Self</a>
        <a href="my_vacation.php"      class="<?= active('my_vacation.php') ?>">My Vacation</a>
      </div>
    </div>

    <a href="marketplace.php" class="<?= active('marketplace.php') ?>">Marketplace</a>
    <a href="my_form.php"     class="<?= active('my_form.php') ?>">My quiz</a>
    <a href="login.php"       class="<?= active('login.php') ?>">To-Do (login)</a>

    <?php if ($user): ?>
      <span style="margin-left:auto;opacity:.85;padding:.25rem .5rem;">Hello, <?= htmlspecialchars($user, ENT_QUOTES, 'UTF-8') ?>!</span>
    <?php endif; ?>
  </div>
</nav>

<script>
// Make dropdown + hamburger work immediately on first load (desktop & mobile)

// Hamburger toggles full link group
const ham = document.getElementById('hamburger');
const links = document.getElementById('nav-links');
if (ham && links) {
  ham.addEventListener('click', () => {
    const open = links.classList.toggle('open');
    ham.setAttribute('aria-expanded', open ? 'true' : 'false');
  });
}

// Dropdown works by click (mobile & desktop). On wide screens it also opens on hover via CSS.
const dropBtn = document.getElementById('dropbtn');
const drop = document.getElementById('dropdown-content');
if (dropBtn && drop) {
  dropBtn.addEventListener('click', (e) => {
    e.preventDefault();
    const open = drop.classList.toggle('open');
    dropBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
  });

  // close when clicking outside
  document.addEventListener('click', (e) => {
    if (!drop.contains(e.target) && !dropBtn.contains(e.target)) {
      drop.classList.remove('open');
      dropBtn.setAttribute('aria-expanded', 'false');
    }
  });
}
</script>
