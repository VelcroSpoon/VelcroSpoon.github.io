<?php
// I keep error display on for this page while developing so that if my quiz
// form has any bugs, I see the warnings directly in the browser instead of
// silently failing somewhere in the PHP.
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>My quiz</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- This stylesheet is shared across the whole site so the quiz page
       looks consistent with my home page, blog, marketplace, etc. -->
  <link rel="stylesheet" href="my_style.css">
  <!-- I load a dedicated JS file for this form (form.js) so all validation
       logic lives in one place instead of mixing it directly into the HTML. -->
  <script src="form.js" defer></script>
</head>
<body>
  <?php
  // I reuse the same nav bar here so users can move between pages (home,
  // quiz, blog, to-do) without the layout shifting from page to page.
  include_once __DIR__ . '/nav.php';
  ?>

  <main class="body_wrapper">
    <!-- This is the main title for the quiz; I phrase it like a BuzzFeed-style
         question to make it feel more fun and less like a boring survey. -->
    <h1>Which type of student are you?</h1>

    <!-- I wrap everything in a <form> that submits to quiz_verification.php.
         Using method="get" lets me see all answers in the URL, which makes
         debugging the scoring logic much easier. -->
    <form id="quiz-form" action="quiz_verification.php" method="get">
      <!-- I use a fieldset to group all the quiz questions visually and
           semantically, which helps both styling and accessibility. -->
      <fieldset>
        <legend>Tell us about you</legend>

        <!-- Name input: I make this required so I can greet the user by name
             on the result page and avoid having to deal with empty strings. -->
        <p>
          <label for="name">Your name</label><br>
          <input type="text" id="name" name="name" placeholder="Jane Doe" required>
        </p>

        <!-- Email input: I use type="email" so the browser can catch obvious
             mistakes (like missing @) before the request even hits PHP. -->
        <p>
          <label for="email">Email</label><br>
          <input type="email" id="email" name="email" placeholder="jane@example.com" required>
        </p>

        <hr>
        <!-- Second legend marks the start of the actual quiz questions so the
             form feels clearly split between “identity” and “answers”. -->
        <legend>Quiz questions</legend>

        <!-- Question 1: I use radio buttons so the student has to commit to
             exactly one planning style, which is important for how I score
             them later in quiz_verification.php. -->
        <p>
          <span>1) How do you plan your study time?</span><br>
          <label><input type="radio" name="plan" value="daily" required> Daily schedule</label><br>
          <label><input type="radio" name="plan" value="weekly"> Weekly blocks</label><br>
          <label><input type="radio" name="plan" value="wing-it"> I wing it</label>
        </p>

        <!-- Question 2: here I switched to checkboxes because I want to let
             people pick multiple tools. Naming the field "tools[]" tells PHP
             to give me a proper array instead of a single string. -->
        <p>
          <span>2) What tools do you use? (choose all that apply)</span><br>
          <label><input type="checkbox" name="tools[]" value="flashcards"> Flashcards</label>
          <label><input type="checkbox" name="tools[]" value="pomodoro"> Pomodoro timer</label>
          <label><input type="checkbox" name="tools[]" value="study-group"> Study group</label>
          <label><input type="checkbox" name="tools[]" value="slides"> Lecture slides</label>
        </p>

        <!-- Question 3: numeric input for daily hours. I clamp it between 0
             and 24 so nobody can accidentally (or jokingly) submit 999 hours
             and break the scoring logic. -->
        <p>
          <label for="hours">3) Avg. study hours per day</label><br>
          <input type="number" id="hours" name="hours" min="0" max="24" step="1" placeholder="e.g., 3" required>
        </p>

        <!-- Question 4: I use a dropdown here because these options are
             mutually exclusive and I don’t want to clutter the page with
             more radio buttons. -->
        <p>
          <label for="time">4) When do you study most?</label><br>
          <select id="time" name="time">
            <option value="morning">Morning</option>
            <option value="afternoon">Afternoon</option>
            <option value="evening">Evening</option>
            <option value="late-night">Late night</option>
          </select>
        </p>

        <!-- Question 5: free-text area so people can describe their strategy
             in their own words. I don't force it to be required because some
             users might not feel like writing a paragraph. -->
        <p>
          <label for="strategy">5) Your #1 study strategy</label><br>
          <textarea id="strategy" name="strategy" rows="4" cols="40" placeholder="Briefly describe…"></textarea>
        </p>

        <!-- Single submit button to fire off the GET request. Any client-side
             validation I do in form.js happens before this actually submits. -->
        <p><button type="submit">Submit</button></p>
      </fieldset>
    </form>
  </main>

  <?php
  // Shared footer so the bottom of this page matches the rest of the site,
  // and I only have to edit one file if I change my footer later.
  include_once __DIR__ . '/footer.php';
  ?>
</body>
</html>
