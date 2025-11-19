<?php
// nav.php
// Don't start a session here — config.php already did.
$u = $_SESSION['username'] ?? ($_COOKIE['todo-username'] ?? null);

// Simple “current page” marker
$current = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '');
function active($file) {
  global $current;
  return $current === $file ? ' class="current_page"' : '';
}
?>
<nav class="site-nav">
  <a href="index.php" class="nav-logo" aria-label="Home">
    <img src="images/bird.png" alt="Simon" />
  </a>

  <div class="nav-links">
    <a<?=active('index.php')?> href="index.php">Home</a>

    <div class="dropdown">
      <button type="button" class="dropbtn">Discover me ▾</button>
      <div class="dropdown-content">
        <a<?=active('my_artistic_self.php')?> href="my_artistic_self.php">My artistic self</a>
        <a<?=active('my_vacation.php')?> href="my_vacation.php">My dream vacation</a>
      </div>
    </div>

    <a<?=active('marketplace.php')?> href="marketplace.php">Marketplace</a>
    <a<?=active('my_form.php')?> href="my_form.php">My quiz</a>
    <a<?=active('login.php')?> href="login.php">To-Do (login)</a>
  </div>

  <?php if ($u): ?>
    <span style="margin-left:auto;opacity:.85;padding:.25rem .5rem;">
      Hello, <?= htmlspecialchars($u, ENT_QUOTES, 'UTF-8') ?>!
    </span>
  <?php endif; ?>

  <button id="hamburger" class="hamburger" aria-expanded="false" aria-controls="navmenu">☰</button>
</nav>
<script>
  // Small JS to toggle hamburger + mobile dropdown click
  (function(){
    const btn = document.getElementById('hamburger');
    const links = document.querySelector('.nav-links');
    if(btn && links){
      btn.addEventListener('click', ()=>{
        const open = links.classList.toggle('open');
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
      });
    }
    document.querySelectorAll('.dropdown .dropbtn').forEach(b=>{
      b.addEventListener('click', (e)=>{
        const menu = b.parentElement.querySelector('.dropdown-content');
        if (getComputedStyle(menu).position === 'static') { // mobile mode
          e.preventDefault();
          menu.classList.toggle('open');
        }
      });
    });
  })();
</script>
