<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>My quiz</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/home/sgrondin/my_style.css">
  <script src="/home/sgrondin/form.js"></script>
</head>
<body>
  <?php include_once __DIR__ . '/nav.php'; ?>

  <main class="body_wrapper">
    <h1>Which type of student are you?</h1>

    <form id="quiz-form" method="get" action="/home/sgrondin/quiz_verification.php" onsubmit="return validate(event)">
      <fieldset>
        <legend>Tell us about you</legend>

        <p>
          <label for="name">Your name</label><br>
          <input type="text" id="name" name="name" placeholder="Jane Doe" required>
        </p>

        <p>
          <label for="email">Email</label><br>
          <input type="email" id="email" name="email" placeholder="jane@example.com">
        </p>

        <hr>
        <legend>Quiz questions</legend>

        <p>
          <span>1) How do you plan your study time?</span><br>
          <label><input type="radio" name="plan" value="daily" required> Daily schedule</label><br>
          <label><input type="radio" name="plan" value="weekly"> Weekly blocks</label><br>
          <label><input type="radio" name="plan" value="wing-it"> I wing it</label>
        </p>

        <p>
          <span>2) What tools do you use? (choose all that apply)</span><br>
          <label><input type="checkbox" name="tools[]" value="flashcards"> Flashcards</label>
          <label><input type="checkbox" name="tools[]" value="pomodoro"> Pomodoro timer</label>
          <label><input type="checkbox" name="tools[]" value="study-group"> Study group</label>
          <label><input type="checkbox" name="tools[]" value="slides"> Lecture slides</label>
        </p>

        <p>
          <label for="hours">3) Avg. study hours per day</label><br>
          <input type="number" id="hours" name="hours" min="0" max="24" step="1" placeholder="e.g., 3">
        </p>

        <p>
          <label for="time">4) When do you study most?</label><br>
          <select id="time" name="time">
            <option value="morning">Morning</option>
            <option value="afternoon">Afternoon</option>
            <option value="evening">Evening</option>
            <option value="late-night">Late night</option>
          </select>
        </p>

        <p>
          <label for="strategy">5) Your #1 study strategy</label><br>
          <textarea id="strategy" name="strategy" rows="4" cols="40" placeholder="Briefly describe…"></textarea>
        </p>

        <p><button type="submit">Submit</button></p>
      </fieldset>
    </form>
  </main>

  <?php include_once __DIR__ . '/footer.php'; ?>
</body>
</html>
