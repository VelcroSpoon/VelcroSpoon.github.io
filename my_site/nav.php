<?php
// nav.php — PHP-driven nav with active highlight, dropdown, hamburger, and optional greeting

// figure out current file (e.g., "index.php")
$__cur = strtolower(basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: 'index.php'));

if (!function_exists('nav_active')) {
  function nav_active($file) {
    $cur = isset($GLOBALS['__cur']) ? $GLOBALS['__cur'] : '';
    return $cur === strtolower($file) ? ' class="current_page"' : '';
  }
}

// pull username from session or cookie, if available
if (session_status() === PHP_SESSION_NONE) { @session_start(); }
$__user = $_SESSION['username'] ?? ($_COOKIE['todo-username'] ?? null);
?>
<nav class="site-nav" role="navigation">
  <!-- Logo → Home -->
  <a href="index.php" class="nav-logo" aria-label="Home">
    <picture>
      <source srcset="images/bird.avif" type="image/avif">
      <!-- Optional PNG fallback; upload images/bird.png if you want this -->
      <img src="images/bird.png" alt="Site logo"
           onerror="this.replaceWith(Object.assign(document.createElement('span'),{textContent:'MySite',className:'logo-text'}));">
    </picture>
  </a>

  <!-- links wrapper (toggled on mobile by hamburger) -->
  <div class="nav-links">
    <a href="index.php"<?php echo nav_active('index.php'); ?>>Home</a>

    <!-- Dropdown -->
    <div class="dropdown">
      <button class="dropbtn" type="button" aria-haspopup="true" aria-expanded="false">
        Discover me! <span aria-hidden="true">▾</span>
      </button>
      <div class="dropdown-content" role="menu" aria-label="Discover me">
        <a href="my_artistic_self.php"<?php echo nav_active('my_artistic_self.php'); ?> role="menuitem">My Artistic Self</a>
        <a href="my_vacation.php"<?php echo nav_active('my_vacation.php'); ?> role="menuitem">My Dream Vacation</a>
      </div>
    </div>

    <a href="marketplace.php"<?php echo nav_active('marketplace.php'); ?>>Marketplace</a>
    <a href="my_form.php"<?php echo nav_active('my_form.php'); ?>>My Quiz</a>
    <a href="login.php"<?php echo nav_active('login.php'); ?>>To-Do</a>

    <?php if ($__user): ?>
      <span style="margin-left:auto;opacity:.8;padding:.25rem .5rem;">
        Hello, <?= htmlspecialchars($__user, ENT_QUOTES, 'UTF-8') ?>!
      </span>
    <?php endif; ?>
  </div>

  <!-- Hamburger (appears < 720px via CSS) -->
  <button class="hamburger" aria-label="Toggle navigation" aria-expanded="false">☰</button>
</nav>

<script>
// Make dropdown + hamburger actually work.
// This script is inline so it runs as soon as nav.php is included.

(() => {
  const nav = document.querySelector('.site-nav');
  if (!nav) return;

  const burger     = nav.querySelector('.hamburger');
  const linksWrap  = nav.querySelector('.nav-links');
  const dropBtn    = nav.querySelector('.dropbtn');
  const dropMenu   = nav.querySelector('.dropdown-content');

  // Hamburger toggle (mobile only — CSS hides/shows .hamburger)
  if (burger && linksWrap) {
    burger.addEventListener('click', () => {
      const open = linksWrap.classList.toggle('open');
      burger.setAttribute('aria-expanded', open ? 'true' : 'false');
      if (!open && dropMenu) dropMenu.classList.remove('open'); // close dropdown when menu closes
    });
  }

  // Dropdown toggle for mobile; desktop opens via :hover in CSS
  if (dropBtn && dropMenu) {
    dropBtn.addEventListener('click', () => {
      const isOpen = dropMenu.classList.toggle('open');
      dropBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
  }
})();
</script>
