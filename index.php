<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Simon Grondin</title>
    <meta name="author" content="Simon Grondin">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="my_style.css">
    <script src="nav.js"></script>
  </head>
  <body>
    <nav id="main-nav" class="site-nav"></nav>
    <script>
      const current_path = location.pathname;
      setNav(current_path);
    </script>

    <div class="body_wrapper">
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

      <p>- I'm a second-year computer science student at Bishop's University. I've been loving it so far as Bishop's has given me plenty of opportunities to meet new people and maintain those connections with the close campus setting. Living far from home also allows you to grow in a way you could never have imagined before.</p>
      <p>- I'm from Ottawa but I've lived in different cities throughout my life, for example I was born in Montreal but we moved to Vancouver for about a year or two. We then moved back in the province of Quebec near Trois-Riviere. Before I started kindergarten, we finally moved to Ottawa, which is where I've been living for the past 17 years or so. Now i'm discovering a new city by coming to Sherbrooke and enjoying a different environment the school has to offer.</p>
      <p>- Currently working towards building a company in hopes of getting financial freedom one day.</p>
      
      <h2>List of Degrees and Schools throughout the years</h2>

      <ul>
        <li>High School Diploma - DeLaSalle High School, Ottawa, ON (Graduated 2022)</li>
        <li>College Diploma - Algonquin College, Ottawa, ON (Graduated 2024)</li>
        <li>Bachelor's Degree in Computer Science - Bishop's University, Sherbrooke, QC (Expected Graduation 2026)</li>
      </ul> 

      <h2>List of interests</h2>
      <ul>
        <li>Working out &amp; playing sports casually</li>
        <li>Reading books, primarily self-help books such as Atomic Habits and Subtle art of not giving a f*ck</li>
        <li>Going to social events and meet new people</li>
        <li>I hate to admit it but I love watching documentaries on Youtube about various topics</li>
      </ul>
    </div>

    <footer>
      This website is made for CS203 labs!
    </footer>
    <script src="slideshow.js"></script>
  </body>
</html>
