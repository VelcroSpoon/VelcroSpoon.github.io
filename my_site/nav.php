<?php
// Optional greeting if you already set username cookie/session elsewhere
session_start();
$u = $_SESSION['username'] ?? ($_COOKIE['todo-username'] ?? null);
?>
<nav class="site-nav">
  <!-- Logo linked to Home; AVIF + PNG fallback -->
  <a href="index.php" class="nav-logo" aria-label="Home" style="display:flex;align-items:center;gap:.5rem;">
    <picture>
      <source srcset="images/bird.avif" type="image/avif">
      <img src="images/bird.png" alt="Site logo" style="height:36px;width:auto;display:block;">
    </picture>
    <span class="logo-text" style="font-weight:700;color:#111;">Simon</span>
  </a>

  <div class="nav-links" style="display:flex;align-items:center;gap:.25rem;margin-left:1rem;">
    <a href="index.php" class="current_page">Home</a>

    <!-- Discover me dropdown (pure CSS hover on desktop) -->
    <div class="dropdown" style="position:relative;">
      <button class="dropbtn" style="background:#333;color:#fff;border:none;padding:.5rem .75rem;border-radius:.5rem;cursor:pointer;">
        Discover me ▾
      </button>
      <div class="dropdown-content" style="display:none;position:absolute;top:100%;left:0;background:#333;min-width:220px;border-radius:.5rem;overflow:hidden;box-shadow:0 8px 20px rgba(0,0,0,.25);z-index:10;">
        <a href="my_artistic_self.php" style="display:block;padding:.6rem .9rem;color:#fff;text-decoration:none;">My Artistic Self</a>
        <a href="my_vacation.php" style="display:block;padding:.6rem .9rem;color:#fff;text-decoration:none;">My Dream Vacation</a>
      </div>
    </div>

    <a href="marketplace.php">Marketplace</a>
    <a href="my_form.php">My quiz</a>
    <a href="login.php">To-Do (login)</a>
  </div>

  <?php if ($u): ?>
    <span style="margin-left:auto;opacity:.8;padding:.25rem .5rem;">
      Hello, <?= htmlspecialchars($u, ENT_QUOTES, 'UTF-8') ?>!
    </span>
  <?php endif; ?>
</nav>

<style>
/* minimal CSS so dropdown works on desktop */
.site-nav a { text-decoration:none; }
.dropdown:hover .dropdown-content { display:block; }
</style>
