<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Simon Grondin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="my_style.css" />
</head>
<body>
  <?php include_once 'nav.php'; ?>

  <main class="body_wrapper">
    <div class="slideshow">
      <div class="slideshow_img"><img src="images/BritishColumbia.jpg" alt="British Columbia"></div>
      <div class="slideshow_img"><img src="images/desertmountains.jpg" alt="Desert mountains"></div>
      <div class="slideshow_img"><img src="images/lavender.jpg" alt="Lavender field"></div>

      <a id="slide-prev" href="javascript:void(0)" onclick="previous()">❮ Prev</a>
      <a id="slide-next" href="javascript:void(0)" onclick="next()">Next ❯</a>
    </div>
  </main>
  <?php include_once 'footer.php'; ?>
  <script src="slideshow.js"></script>
</body>
</html>
