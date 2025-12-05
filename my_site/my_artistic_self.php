<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Port at Night</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- This page is my “artistic self” page: I use it to connect a piece of art
       to key personality keywords and a longer reflection about myself. -->
  <link rel="stylesheet" href="my_style.css">
</head>
<body class="art-page">
  <?php
  // I reuse the same navigation bar here so the user can move between my
  // portfolio pages (home, quiz, marketplace, blog, etc.) without the layout
  // changing from one section to another.
  include_once 'nav.php';
  ?>

  <main class="body_wrapper">
    <!-- This outer container keeps all the art content in a consistent width
         and spacing so it matches the rest of the site layout. -->
    <div class="art-container">
      <h1>Port at Night</h1>

      <!-- I split the page into two main columns:
           - left: short “keyword” labels
           - right: the painting and my longer explanation
           This mirrors how someone might introduce themselves with both
           quick labels and a deeper story. -->
      <div class="art-row">
        <div class="art-left">
          <h2>Keywords</h2>
          <!-- Each keyword is its own element so I can style them individually
               (or even attach hover effects later) without changing the text. -->
          <p class="keyword" id="kw1">Passionate</p>
          <p class="keyword" id="kw2">Curious</p>
          <p class="keyword" id="kw3">Improvement</p>
          <p class="keyword" id="kw4">Serenity</p>
          <p class="keyword" id="kw5">Dogs</p>
        </div>

        <div class="art-right">
          <!-- This frame wraps the image so the CSS can control sizing,
               borders, and alignment without touching the <img> directly. -->
          <div class="art-frame">
            <img src="images/port_at_night.avif"
                 alt="Nighttime port with reflections on the water">
          </div>

          <!-- This block holds my personal reflection where I connect the
               painting to specific parts of my life (mental health, habits,
               nature, and my dogs). I keep it in one <p> so it reads like
               a single narrative rather than a list of bullet points. -->
          <div class="art-text">
            <p>
              I believe this painting reflects me relatively well as I enjoy the peace and quiet you experience at
              night while you watch the stars. This may seem like I enjoy nature fully but that's not completely true
              as I still appreciate signs of civilisation as you can see in the painting picturing a port. Something
              about that middle ground is great to me. I am passionate about certain topics and believe to be a very
              ambitious person with many plans in mind. Although I struggle to accomplish them sometimes, I try my
              best to stay true to myself and complete as many goals in life as I can, after all life is short.
              In a similar manner, I'm a curious person as I love to learn about most things, the world is filled with
              plenty of mysteries and questions that have yet to be answered. I've only recently rediscovered my
              curious side as I went through a rough time previously but since I've gotten better, I've been
              rediscovering myself again slowly. Improvement has been a life changing aspect of my life. As mentioned
              previously I've been struggling mentally as I was not exactly where I was supposed to be. I've always had
              a hard time accepting myself and now it's definitely gotten better. Reading books about how to get better
              habits or how to simply be a better person everyday helps a lot to feel more fulfilled about myself and
              believe that I'm not simply not making any progress in life. I believe the past 2 to 3 years have been the
              most progress I've made as a person and I'm proud of it.
              Now serenity, I love the peace and quiet you can find in nature, especially at night. I love to go for
              walks at night and just admire the stars and the calmness that comes with it. It's a great way to clear
              your mind and relax. Lastly, dogs. I had 2 dogs growing up. One of them passed away last year but I still
              love them both dearly. Dogs are great friends and help you understand that sometimes life being simple is
              fine. I love making my dogs happy by taking them on walks as much as possible or giving them treats. My
              dogs would walk for the whole day if they could. I love them both so much and they are definitely a big
              part of my life.
            </p>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php
  // Footer is shared across the site so every page ends the same way and I
  // don’t repeat the HTML in multiple files.
  include_once 'footer.php';
  ?>
</body>
</html>
