<?php
// nav.php — PHP include for the top navigation (cookie-only auth compatible)

// figure out current path for "active" state
$cur = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$active = function(string $path) use ($cur) {
  return $cur === $path ? ' class="current_page"' : '';
};

// optional greeting from cookie (set after login)
$username = $_COOKIE['todo-username'] ?? null;
?>
<nav class="site-nav" role="navigation">
  <!-- Logo + brand -->
  <a href="/home/sgrondin/index.php" class="nav-logo" aria-label="Home" style="display:flex;align-items:center;gap:.5rem">
    <picture>
      <source srcset="/home/sgrondin/images/bird.avif" type="image/avif">
      <img src="/home/sgrondin/images/bird.png" alt="Site logo" style="height:36px;width:auto;display:block;">
    </picture>
    <span class="logo-text" style="font-weight:700;">Simon</span>
  </a>

  <!-- Mobile hamburger -->
  <button id="hamburger" class="hamburger" aria-controls="nav-links" aria-expanded="false">☰ Menu</button>

  <!-- Links -->
  <div id="nav-links" class="nav-links">
    <a<?= $active('/home/sgrondin/index.php') ?> href="/home/sgrondin/index.php">Home</a>

    <div class="dropdown">
      <button class="dropbtn" type="button">Discover me ▾</button>
      <div class="dropdown-content" id="discover-dropdown">
        <a<?= $active('/home/sgrondin/my_artistic_self.php') ?> href="/home/sgrondin/my_artistic_self.php">My Artistic Self</a>
        <a<?= $active('/home/sgrondin/my_vacation.php') ?> href="/home/sgrondin/my_vacation.php">My Dream Vacation</a>
      </div>
    </div>

    <a<?= $active('/home/sgrondin/marketplace.php') ?> href="/home/sgrondin/marketplace.php">Marketplace</a>
    <a<?= $active('/home/sgrondin/my_form.php') ?> href="/home/sgrondin/my_form.php">My quiz</a>
    <a<?= $active('/home/sgrondin/login.php') ?> href="/home/sgrondin/login.php">To-Do</a>

    <?php if ($username): ?>
      <span style="margin-left:auto;opacity:.85;padding:.25rem .5rem;">Hello, <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>!</span>
    <?php endif; ?>
  </div>
</nav>

<script>
// Hamburger + mobile dropdown
(function(){
  const btn  = document.getElementById('hamburger');
  const list = document.getElementById('nav-links');
  if (btn && list){
    btn.addEventListener('click', () => {
      const open = list.classList.toggle('open');
      btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }
  const dropBtn = document.querySelector('.dropbtn');
  const drop    = document.getElementById('discover-dropdown');
  if (dropBtn && drop){
    dropBtn.addEventListener('click', () => drop.classList.toggle('open'));
  }
})();
</script>
