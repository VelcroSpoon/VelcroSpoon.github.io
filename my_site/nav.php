<?php require_once __DIR__.'/includes/config.php'; ?>
<nav class="site-nav">
  <!-- Logo link -->
  <a href="<?= BASE_URL ?>index.php" class="nav-logo" aria-label="Home" style="display:flex;align-items:center;gap:.5rem;">
    <picture>
      <source srcset="<?= BASE_URL ?>images/bird.avif" type="image/avif">
      <img src="<?= BASE_URL ?>images/bird.png" alt="Site logo" style="height:36px;width:auto;display:block;">
    </picture>
    <span class="logo-text">Simon</span>
  </a>

  <!-- Hamburger (mobile) -->
  <button class="hamburger" type="button" aria-label="Toggle navigation" aria-expanded="false">☰</button>

  <!-- Links -->
  <div class="nav-links">
    <a href="<?= BASE_URL ?>index.php">Home</a>

    <div class="dropdown">
      <button class="dropbtn" type="button" aria-expanded="false">Discover me ▾</button>
      <div class="dropdown-content">
        <a href="<?= BASE_URL ?>my_artistic_self.php">My Artistic Self</a>
        <a href="<?= BASE_URL ?>my_vacation.php">My Vacation</a>
      </div>
    </div>

    <a href="<?= BASE_URL ?>marketplace.php">Marketplace</a>
    <a href="<?= BASE_URL ?>my_form.php">My quiz</a>
    <a href="<?= BASE_URL ?>login.php">To-Do</a>
  </div>

  <?php
  // Optional greeting
  $u = $_SESSION['username'] ?? ($_COOKIE['todo-username'] ?? null);
  if ($u): ?>
    <span style="margin-left:auto;opacity:.8;padding:.25rem .5rem;">Hello, <?= h($u) ?>!</span>
  <?php endif; ?>
</nav>

<script>
// Minimal JS (safe inline) for hamburger and dropdown
(() => {
  const nav = document.currentScript.previousElementSibling; // the <nav>
  const hamburger = nav.querySelector('.hamburger');
  const linksWrap = nav.querySelector('.nav-links');
  const dropBtn = nav.querySelector('.dropbtn');
  const dropContent = nav.querySelector('.dropdown-content');

  if (hamburger) {
    hamburger.addEventListener('click', () => {
      const open = linksWrap.classList.toggle('open');
      hamburger.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }
  if (dropBtn && dropContent) {
    dropBtn.addEventListener('click', () => {
      const open = dropContent.classList.toggle('open');
      dropBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }
})();
</script>
