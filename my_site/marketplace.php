<?php /* marketplace.php */ ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Marketplace</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="my_style.css">
  <!-- load script AFTER markup -->
  <script src="marketplace.js" defer></script>
</head>
<body>
  <?php include_once 'nav.php'; ?>

  <main class="body_wrapper">
    <h1>Marketplace</h1>

    <!-- Search bar + button + helper chips -->
    <section style="max-width:900px;margin:12px 0 20px 0;">
      <label for="mp-q" class="sr-only">Search products</label>
      <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
        <input id="mp-q" type="text" placeholder="Try: apple, brush, bicycle, hammer, or ≤20"
               style="flex:1 1 340px;padding:10px 12px;border:1px solid #cfd7e6;border-radius:10px;">
        <button id="mp-btn" type="button"
                style="padding:10px 14px;border:0;border-radius:10px;background:#0b1220;color:#fff;font-weight:600;cursor:pointer;">
          Search
        </button>
      </div>
      <div id="mp-suggestions" style="margin-top:8px;"></div>
    </section>

    <!-- Results grid -->
    <section>
      <div id="mp-list" aria-live="polite"></div>
    </section>

    <!-- Visible inventory list to click/filter -->
    <section style="margin-top:28px;max-width:900px;">
      <h2 style="margin-bottom:8px;">Available items</h2>
      <ul id="mp-ul" style="padding-left:18px;line-height:1.75;"></ul>
    </section>
  </main>

  <?php include_once 'footer.php'; ?>
</body>
</html>
