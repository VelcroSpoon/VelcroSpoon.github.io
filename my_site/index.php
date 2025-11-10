<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Simon Grondin</title>
  <meta name="author" content="Simon Grondin">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="my_style.css">
</head>
<body>
  <?php include_once 'nav.php'; ?>

  <main class="body_wrapper">
    <h1>Hi, my name is Simon Grondin.</h1>

    <div class="slideshow">
      <div class="slideshow_img">
        <img src="images/BritishColumbia.jpg" alt="Mountain lake in Canada at sunrise">
      </div>
      <div class="slideshow_img">
        <img src="images/desertmountains.jpg" alt="Desert mountains with cactuses at sunset">
      </div>
      <div class="slideshow_img">
        <img src="images/lavender.jpg" alt="Lake with flowers and mountain in the background">
      </div>

      <a id="slide-prev" href="javascript:void(0)" onclick="previous()">❮ Prev</a>
      <a id="slide-next" href="javascript:void(0)" onclick="next()">Next ❯</a>
    </div>

    <h2>About me</h2>
    <p>- I'm a second-year computer science student at Bishop's University…</p>
  </main>

  <?php include_once 'footer.php'; ?>
  <script src="slideshow.js"></script>
</body>
</html>
