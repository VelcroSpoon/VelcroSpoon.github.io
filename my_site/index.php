<?php
// This is my main home page. It acts as the “landing page” for the whole site
// and doesn’t need any PHP logic right now, but I keep the PHP tag here in case
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Simon Grondin</title>
  <meta name="author" content="Simon Grondin">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Shared site-wide stylesheet (handles layout, nav, cards, slideshow styling, etc.) -->
  <link rel="stylesheet" href="my_style.css">
</head>
<body>
  <?php
  // I reuse the same navigation bar across all pages so the menu, current-page
  // highlight, and hamburger dropdown are centralized in nav.php instead of duplicated.
  include_once __DIR__ . '/nav.php';
  ?>

  <main class="body_wrapper">
    <!--
      HERO section: this top block combines my personal intro and the slideshow.
      The left side is the “who I am” text, and the right side is a rotating set
      of images that makes the page feel more like a portfolio than just plain text.
    -->
    <section class="home-hero">
      <div class="home-hero-text">
        <h1>Hi, my name is Simon Grondin.</h1>
        <p>
          I’m a second-year computer science student at Bishop’s University,
          building projects, exploring new cities, and working toward financial freedom.
        </p>
      </div>

      <!--
        Slideshow container: each .slideshow_img is one slide in the gallery.
        The previous() and next() functions (defined in slideshow.js) decide
        which of these images is currently visible when the user clicks the arrows.
      -->
      <div class="home-hero-slideshow">
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

          <!--
            These links don’t navigate anywhere; they just call JS functions.
            I keep href="javascript:void(0)" to avoid page jumps while still
            using <a> elements for keyboard accessibility.
          -->
          <a id="slide-prev" href="javascript:void(0)" onclick="previous()">❮ Prev</a>
          <a id="slide-next" href="javascript:void(0)" onclick="next()">Next ❯</a>
        </div>
      </div>
    </section>

    <!--
      About card: this section goes a bit deeper into my background,
      explaining my move away from home and my long-term goals.
      I keep it in its own card so it feels like one focused block instead
      of being mixed with other page content.
    -->
    <section class="home-section">
      <h2>About me</h2>
      <p>
        - I'm a second-year computer science student at Bishop's University. I've been loving it so far
        as Bishop's has given me plenty of opportunities to meet new people and maintain those connections
        with the close campus setting. Living far from home also allows you to grow in a way you could
        never have imagined before.
      </p>
      <p>
        - I'm from Ottawa but I've lived in different cities throughout my life… now I’m discovering a new
        city by coming to Sherbrooke and enjoying a different environment the school has to offer.
      </p>
      <p>
        - Currently working towards building a company in hopes of getting financial freedom one day.
      </p>
    </section>

    <!--
      Two-column layout: here I split my academic path and my interests into
      two separate cards. This makes it easier to scan quickly and also shows
      both the “school” side and the “personality/hobbies” side of me.
    -->
    <section class="home-columns">
      <section class="home-column">
        <h2>Degrees &amp; Schools</h2>
        <ul>
          <li>High School Diploma — DeLaSalle (2022)</li>
          <li>College Diploma — Algonquin (2024)</li>
          <li>BSc CS — Bishop’s (Expected 2026)</li>
        </ul>
      </section>

      <section class="home-column">
        <h2>Interests</h2>
        <ul>
          <li>Working out &amp; casual sports</li>
          <li>Self-help books (Atomic Habits, etc.)</li>
          <li>Going to social events and meeting new people</li>
          <li>Documentaries on YouTube</li>
        </ul>
      </section>
    </section>
  </main>

  <?php
  // Footer is also shared across the site, so I include it once here
  // instead of repeating the same HTML at the bottom of every page.
  include_once __DIR__ . '/footer.php';
  ?>

  <!--
    The slideshow behaviour (which slide is visible, how next/prev work)
    is kept in its own JS file to respect the “separate JS from HTML” rule
    from the assignment instructions.
  -->
  <script src="slideshow.js"></script>
</body>
</html>
