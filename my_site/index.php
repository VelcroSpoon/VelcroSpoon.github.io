<?php
// Show PHP errors while you fix things locally. Remove later.
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Simon Grondin</title>
  <meta name="author" content="Simon Grondin">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- RELATIVE path so it works in a subfolder -->
  <link rel="stylesheet" href="my_style.css">
  <style>
    /* Keep nav above the slideshow; prevents hamburger from being hidden */
    .site-nav { position: sticky; top: 0; z-index: 1000; }
    .slideshow { margin-top: 12px; }
    #slide-prev, #slide-next { z-index: 10; }
  </style>
</head>
<body>
  <?php include_once __DIR__ . '/nav.php'; ?>

  <main class="body_wrapper">
    <h1>Hi, my name is Simon Grondin.</h1>

    <div class="slideshow">
      <div class="slideshow_img"><img src="images/BritishColumbia.jpg" alt="Mountain lake in Canada at sunrise"></div>
      <div class="slideshow_img"><img src="images/desertmountains.jpg" alt="Desert mountains with cactuses at sunset"></div>
      <div class="slideshow_img"><img src="images/lavender.jpg" alt="Lake with flowers and mountain in the background"></div>

      <a id="slide-prev" href="javascript:void(0)" onclick="previous()">❮ Prev</a>
      <a id="slide-next" href="javascript:void(0)" onclick="next()">Next ❯</a>
    </div>

    <h2>About me</h2>
    <p>- I'm a second-year computer science student at Bishop's University. I've been loving it so far as Bishop's has given me plenty of opportunities to meet new people and maintain those connections with the close campus setting. Living far from home also allows you to grow in a way you could never have imagined before.</p>
    <p>- I'm from Ottawa but I've lived in different cities throughout my life… now I’m discovering a new city by coming to Sherbrooke and enjoying a different environment the school has to offer.</p>
    <p>- Currently working towards building a company in hopes of getting financial freedom one day.</p>

    <h2>List of Degrees and Schools throughout the years</h2>
    <ul>
      <li>High School Diploma — DeLaSalle (2022)</li>
      <li>College Diploma — Algonquin (2024)</li>
      <li>BSc CS — Bishop’s (Expected 2026)</li>
    </ul>

    <h2>List of interests</h2>
    <ul>
      <li>Working out &amp; casual sports</li>
      <li>Self-help books (Atomic Habits, etc.)</li>
      <li>Going to social events and meeting new people</li>
      <li>Documentaries on YouTube</li>
    </ul>
  </main>

  <?php include_once __DIR__ . '/footer.php'; ?>
  <script src="slideshow.js"></script>
</body>
</html>
