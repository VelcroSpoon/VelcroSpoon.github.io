<?php require_once __DIR__ . '/config.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Marketplace</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?= BASE_URL ?>my_style.css">
</head>
<body>
  <?php include __DIR__ . '/nav.php'; ?>

  <main class="body_wrapper">
    <h1>Marketplace</h1>

    <div id="market" class="container">
      <div class="product-card" style="border:1px solid #ccc;border-radius:10px;padding:12px;margin-bottom:12px;">
        <strong>Sample Listing</strong>
        <p>A friendly placeholder while JavaScript loads.</p>
      </div>
    </div>
  </main>

  <?php include __DIR__ . '/footer.php'; ?>
  <script src="<?= BASE_URL ?>marketplace.js"></script>
</body>
</html>
