<?php /* marketplace.php */ ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Marketplace</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- keep this path RELATIVE so it works on XAMPP in a subfolder and on Osiris -->
  <link rel="stylesheet" href="my_style.css">
</head>
<body>
  <?php include_once 'nav.php'; ?>

  <main class="body_wrapper">
    <h1>Marketplace</h1>
    <p>Welcome!</p>
<?php /* marketplace.php */ ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Marketplace</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="my_style.css">
  <style>
    /* minimal page-specific styles */
    .market-toolbar{
      display:flex; align-items:center; gap:.5rem; flex-wrap:wrap; margin-bottom:14px;
    }
    .market-toolbar input[type="search"]{
      padding:.55rem .75rem; border:1px solid #cfd7e6; border-radius:.6rem; min-width:240px;
    }
    .cart-pill{
      margin-left:auto; background:#0b1220; color:#fff; padding:.4rem .6rem; border-radius:999px; font-weight:600;
    }
    .market-grid{
      display:grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      gap:14px;
    }
    .card{
      border:1px solid #d6deee; border-radius:12px; overflow:hidden; background:#fff;
      display:flex; flex-direction:column;
      box-shadow:0 2px 10px rgba(11,18,32,.06);
    }
    .card img{ width:100%; height:160px; object-fit:cover; display:block; }
    .card .body{ padding:.8rem .9rem; flex:1 1 auto; }
    .card h3{ margin:.1rem 0 .35rem 0; font-size:1rem; }
    .card .price{ font-weight:700; }
    .card button{
      margin:.6rem .9rem .9rem .9rem; border:none; border-radius:.6rem;
      background:#0b1220; color:#fff; padding:.55rem .8rem; cursor:pointer;
    }
    .empty{
      opacity:.7; border:1px dashed #cfd7e6; padding:1rem; border-radius:.6rem; text-align:center;
    }
  </style>
</head>
<body>
  <?php include_once 'nav.php'; ?>

  <main class="body_wrapper">
    <h1>Marketplace</h1>

    <div class="market-toolbar">
      <input id="search" type="search" placeholder="Search prints…">
      <span id="cart-pill" class="cart-pill">Cart: <span id="cart-count">0</span></span>
    </div>

    <div id="market" class="market-grid" aria-live="polite"></div>
    <p id="empty" class="empty" style="display:none;">No items match your search.</p>
  </main>

  <?php include_once 'footer.php'; ?>
  <script src="marketplace.js"></script>
</body>
</html>
    <div id="market-container" class="container"></div>
  </main>

  <?php include_once 'footer.php'; ?>
  <script src="marketplace.js"></script>
</body>
</html>
