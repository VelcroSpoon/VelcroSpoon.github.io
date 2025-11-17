<?php require_once __DIR__.'/includes/config.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Simon Grondin — Home</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?= BASE_URL ?>my_style.css">
  <style>
    /* keep nav above */
    .site-nav{ position:sticky; top:0; z-index:1000; }
    .slideshow{ margin-top:12px; }
    #slide-prev,#slide-next{ z-index:10; }
  </style>
</head>
<body>
  <?php include __DIR__.'/nav.php'; ?>

  <main class="body_wrapper">
    <h1>Hi, my name is Simon Grondin.</h1>

    <div class="slideshow">
      <div class="slideshow_img">
        <img src="<?= BASE_URL ?>images/BritishColumbia.jpg"
             alt="Mountain lake in Canada at sunrise">
      </div>
      <div class="slideshow_img">
        <img src="<?= BASE_URL ?>images/desertmountains.jpg"
             alt="Desert mountains with cactuses at sunset">
      </div>
      <div class="slideshow_img">
        <img src="<?= BASE_URL ?>images/lavender.jpg"
             alt="Lake with flowers and mountain in the background">
      </div>

      <a id="slide-prev" href="javascript:void(0)" onclick="previous()">❮ Prev</a>
      <a id="slide-next" href="javascript:void(0)" onclick="next()">Next ❯</a>
    </div>

    <h2>About me</h2>
    <p>I'm a second-year computer science student at Bishop’s University. I love the close-knit campus vibe and the opportunities to meet people and collaborate.</p>
    <p>I grew up mostly in Ottawa with stops in Montréal and Vancouver. Now I’m in Sherbrooke exploring a new city and environment.</p>
    <p>Long-term, I’m working toward financial freedom by building useful software and learning business skills.</p>

    <h2>List of Degrees and Schools</h2>
    <ul>
      <li>High School — De La Salle HS, Ottawa (2022)</li>
      <li>College Diploma — Algonquin College, Ottawa (2024)</li>
      <li>B.Sc. CS — Bishop’s University (Expected 2026)</li>
    </ul>

    <h2>Interests</h2>
    <ul>
      <li>Working out &amp; casual sports</li>
      <li>Reading self-improvement (e.g., <em>Atomic Habits</em>)</li>
      <li>Events + meeting new people</li>
      <li>Documentaries on all kinds of topics</li>
    </ul>
  </main>

  <?php include __DIR__.'/footer.php'; ?>
  <script src="<?= BASE_URL ?>slideshow.js"></script>
</body>
</html>
