<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>My dream vacations</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- I reuse the same main stylesheet so this page keeps the same card layout
       and typography as the rest of the site (home, blog, quiz, etc.). -->
  <link rel="stylesheet" href="my_style.css">
</head>
<body>
  <?php
  // I include the shared navigation here so the user can jump back to any other
  // page (home, marketplace, blog…) without duplicating nav markup on every file.
  include_once 'nav.php';
  ?>

  <main class="body_wrapper">
    <!-- PAGE HEADER
         This header block introduces the whole “dream vacations” page and gives
         a short explanation of what the user is looking at plus a quick link
         to go back home. -->
    <header class="vacation-header">
      <h1>My dream vacations</h1>
      <p>
        A few of the places I’d love to visit one day — a mix of family, food, and iconic landmarks.
      </p>
      <p class="vacation-back">
        ← <a href="index.php">Back to home</a>
      </p>
    </header>

    <!-- JAPAN
         This first section uses the shared “vacation-country” layout so every
         country looks consistent: text on one side and images on the other. -->
    <section class="vacation-country">
      <div class="vacation-country-inner">
        <!-- Text column for Japan: I split it into small headings and paragraphs
             so it’s easier to skim instead of one big wall of text. -->
        <div class="vacation-country-text">
          <h2>Japan</h2>
          <h3>Exploring the countryside</h3>
          <p>
            I’d love to experience the quiet side of Japan and reconnect with my
            <strong>grandma</strong> who lives there.
          </p>

          <h3>Favorite food: Zaru Soba</h3>
          <p>
            One of my favourite dishes — simple, refreshing, and something I always
            associate with visiting family.
          </p>
        </div>

        <!-- Image column for Japan: I show both the countryside and the food
             so it matches the two main things I talk about in the text. -->
        <div class="vacation-country-image">
          <img
            src="images/countryside.webp"
            alt="Countryside in Japan"
            class="vacation-img"
          >
          <br><br>
          <img
            src="images/Soba.jpg"
            alt="Zaru Soba dish"
            class="vacation-img"
          >
        </div>
      </div>
    </section>

    <!-- ITALY
         Same pattern as Japan: I keep the layout identical but change the content
         so the page feels like a series of “cards” for each country. -->
    <section class="vacation-country">
      <div class="vacation-country-inner">
        <div class="vacation-country-text">
          <h2>Italy</h2>
          <h3>Italian countryside</h3>
          <p>
            I imagine spending slow days in the <strong>Italian countryside</strong>, enjoying good food,
            warm weather, and peaceful views.
          </p>

          <h3>History in Rome</h3>
          <p>
            Visiting the <strong>Colosseum</strong> and walking through ancient streets would be a dream.
          </p>
        </div>

        <!-- Two images again: one for the countryside vibe and one for the more
             “classic tourist” side with the Colosseum. -->
        <div class="vacation-country-image">
          <img
            src="images/Italy_countryside.avif"
            alt="Italian countryside"
            class="vacation-img"
          >
          <br><br>
          <img
            src="images/Colosseum.jpg"
            alt="The Colosseum in Rome"
            class="vacation-img"
          >
        </div>
      </div>
    </section>

    <!-- FRANCE
         Third country block, reusing the same structure so the CSS grid can
         handle everything in a consistent way. -->
    <section class="vacation-country">
      <div class="vacation-country-inner">
        <div class="vacation-country-text">
          <h2>France</h2>
          <h3>Mont-Saint-Michel</h3>
          <p>
            I’d love to see <strong>Mont-Saint-Michel</strong> from the outside and explore the surrounding area.
          </p>

          <h3>Palace of Versailles</h3>
          <p>
            Touring the <strong>Palace of Versailles</strong> and its gardens is high on my list of must-see places.
          </p>
        </div>

        <!-- Visuals again line up directly with the two headings above:
             first Mont-Saint-Michel, then Versailles. -->
        <div class="vacation-country-image">
          <img
            src="images/Mont-Saint-Michel-Normandy.jpg"
            alt="Mont-Saint-Michel"
            class="vacation-img"
          >
          <br><br>
          <img
            src="images/Palace-of-Versailles.jpg"
            alt="Palace of Versailles"
            class="vacation-img"
          >
        </div>
      </div>
    </section>

    <!-- SMALL CLOSING NOTE
         I end the page with a short summary paragraph in its own card so the user
         doesn’t feel like the page just stops after the last set of photos. -->
    <section class="vacation-country">
      <p class="vacation-footer-text">
        There are many more places I’d like to visit that come to mind, but these are the
        <strong>main</strong> ones I think about when I imagine future trips.
      </p>
    </section>
  </main>

  <?php
  // Shared footer so copyright / footer content is controlled in one place
  // and automatically shows up on every page of the site.
  include_once 'footer.php';
  ?>
</body>
</html>
