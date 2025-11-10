<?php
// Robust active helper (unique name + no short echo)
$__cur = strtolower(basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: 'index.php'));
if (!function_exists('nav_active')) {
  function nav_active($file) {
    $cur = isset($GLOBALS['__cur']) ? $GLOBALS['__cur'] : '';
    return $cur === strtolower($file) ? ' class="current_page"' : '';
  }
}
?>
<nav class="site-nav" role="navigation">
  <!-- Logo links Home -->
  <a href="index.php" class="nav-logo" aria-label="Home">
    <picture>
      <source srcset="images/bird.avif" type="image/avif">
      <img src="images/bird.avif" alt="Site logo">
    </picture>
  </a>

  <!-- Links wrapper (shown/hidden by hamburger on small screens) -->
  <div class="nav-links">
    <a href="index.php"<?php echo nav_active('index.php'); ?>>Home</a>

    <!-- Dropdown: Discover me! -->
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
    <a href="to-do.php"<?php echo nav_active('to-do.php'); ?>>To-Do</a>
  </div>

  <!-- Hamburger (appears on small screens) -->
  <button class="hamburger" aria-label="Toggle navigation" aria-expanded="false">☰</button>
</nav>

<script>
// Hamburger + mobile dropdown toggle (desktop uses :hover)
(function(){
  const nav = document.querySelector('.site-nav');
  const burger = nav?.querySelector('.hamburger');
  const linksWrap = nav?.querySelector('.nav-links');
  if (burger && linksWrap) {
    burger.addEventListener('click', ()=>{
      const open = linksWrap.classList.toggle('open');
      burger.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }
  const dropBtn = nav?.querySelector('.dropbtn');
  const dropContent = nav?.querySelector('.dropdown-content');
  if (dropBtn && dropContent) {
    dropBtn.addEventListener('click', ()=>{
      // On mobile (when nav is stacked), toggle by click
      const open = dropContent.classList.toggle('open');
      dropBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }
})();
</script>
