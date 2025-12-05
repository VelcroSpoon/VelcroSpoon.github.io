<?php
// I turn on error display for this page while developing/debugging the quiz result logic.
// This makes PHP notices or mistakes visible in the browser instead of failing silently.
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Before doing any calculations, I validate that the minimum required inputs
// actually came from the quiz form. Here I require a non-empty name and a chosen plan.
// If those are missing, I immediately show a friendly error page instead of
// trying to run the scoring logic with bad data.
if (
  !isset($_GET['name']) || trim($_GET['name']) === '' ||
  !isset($_GET['plan'])
){
  http_response_code(400);
  $msg = "Missing required inputs. Please complete the form.";
  ?>
  <!doctype html>
  <html lang="en"><head>
    <meta charset="utf-8"><title>Form error</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="my_style.css">
  </head><body>
  <?php include_once __DIR__ . '/nav.php'; ?>
  <main class="body_wrapper">
    <h1>Form error</h1>
    <p style="color:#b91c1c;"><?=$msg?></p>
    <p><a href="my_form.php">Go back to the quiz</a></p>
  </main>
  <?php include_once __DIR__ . '/footer.php'; ?>
  </body></html>
  <?php
  // I exit here so the rest of the script (the scoring and result page)
  // doesn’t run when the form is incomplete.
  exit;
}

// At this point I know I have the minimum required data, so I can start
// preparing the values for scoring and display.

// I trim and escape the name immediately so I can safely echo it back
// in the HTML without worrying about special characters or HTML injection.
$name  = htmlspecialchars(trim($_GET['name']), ENT_QUOTES, 'UTF-8');

// For the plan, I keep the raw value (no HTML output yet) and default
// to "wing-it" if somehow it’s not set.
$plan  = $_GET['plan'] ?? 'wing-it';

// Tools and hours are optional: tools is an array of checkboxes and
// hours is converted to an integer for easier math in the scoring.
$tools = $_GET['tools'] ?? [];
$hours = (int)($_GET['hours'] ?? 0);

// This block converts quiz answers into a numeric score out of 100.
// I use simple weights so it’s easy to explain in the lab report and tweak later.
$score = 0;

// Daily planning is treated as the most “organized” behaviour, so it gets
// the biggest chunk of points. Weekly planning is still structured, but a bit less.
if ($plan === 'daily')  $score += 40;
if ($plan === 'weekly') $score += 25;

// Study hours scale linearly up to a cap (max 25 points) so someone
// studying all day doesn’t completely blow up the score.
$score += min($hours * 5, 25);

// Each tool adds a fixed bonus, but only if tools is actually an array.
// This rewards using multiple strategies without having to inspect which ones.
$score += (is_array($tools) ? count($tools) : 0) * 5;

// Just in case the combination of answers overshoots, I hard-cap at 100.
// This guarantees the score is always between 0 and 100 for display.
if ($score > 100) $score = 100;

// Based on the final score, I classify the student into one of two categories.
// I keep the threshold at 50 so it’s easy to understand and balance.
$category = ($score >= 50) ? 'Organized' : 'Chaotic';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Quiz Result</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="my_style.css">
</head>
<body>
  <?php include_once __DIR__ . '/nav.php'; ?>

  <main class="body_wrapper">
    <!-- I greet the user by name using the already-escaped value,
         so the header feels more personal and still stays safe. -->
    <h1>Hi <?= $name ?>, your result is:</h1>
    <p><strong>Score:</strong> <?= $score ?>/100</p>

    <!-- I highlight both categories side by side so students can see
         where they landed, but also compare to the “other” type.
         The inline style adds a subtle background color only on the
         box that matches the current category. -->
    <section style="display:grid;gap:12px;max-width:720px;">
      <div style="border:1px solid #ddd;border-radius:10px;padding:12px;<?= $category==='Organized'?'background:#ecfdf5;border-color:#34d399;':'' ?>">
        <h2>Organized (≥ 50)</h2>
        <p>You plan ahead, track your time, and keep consistent habits.</p>
      </div>
      <div style="border:1px solid #ddd;border-radius:10px;padding:12px;<?= $category==='Chaotic'?'background:#fef2f2;border-color:#f87171;':'' ?>">
        <h2>Chaotic (&lt; 50)</h2>
        <p>You improvise more often than you plan. Try small routines to build momentum.</p>
      </div>
    </section>
  </main>

  <?php include_once __DIR__ . '/footer.php'; ?>
</body>
</html>
