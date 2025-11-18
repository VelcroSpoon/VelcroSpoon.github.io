<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Simon Grondin</title>
  <meta name="author" content="Simon Grondin">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/home/sgrondin/my_style.css">
  <style>
    /* Keep nav above slideshow; give slideshow a little breathing room */
    .site-nav { position: sticky; top: 0; z-index: 1000; }
    .slideshow { margin-top: 12px; }
    #slide-prev,#slide-next { z-index: 10; }
  </style>
</head>
<body>
  <?php include_once __DIR__ . '/nav.php'; ?>

  <main class="body_wrapper">
    <h1>Hi, my name is Simon Grondin.</h1>

    <div class="slideshow">
      <div class="slideshow_img"><img src="/home/sgrondin/images/BritishColumbia.jpg" alt="Mountain lake in Canada at sunrise"></div>
      <div class="slideshow_img"><img src="/home/sgrondin/images/desertmountains.jpg"  alt="Desert mountains with cactuses at sunset"></div>
      <div class="slideshow_img"><img src="/home/sgrondin/images/lavender.jpg"         alt="Lake with flowers and mountain in the background"></div>

      <a id="slide-prev" href="javascript:void(0)" onclick="previous()">❮ Prev</a>
      <a id="slide-next" href="javascript:void(0)" onclick="next()">Next ❯</a>
    </div>

    <h2>About me</h2>
    <p>- I'm a second-year computer science student at Bishop's University...</p>
    <p>- I'm from Ottawa ... enjoying Sherbrooke’s environment...</p>
    <p>- Currently working towards building a company...</p>

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
      <li>Documentaries on YouTube (don’t judge 😅)</li>
    </ul>
  </main>

  <?php include_once __DIR__ . '/footer.php'; ?>
  <script src="/home/sgrondin/slideshow.js"></script>
</body>
</html>
