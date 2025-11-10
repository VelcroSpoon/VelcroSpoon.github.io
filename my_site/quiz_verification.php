<?php
function h($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }

$name     = $_GET['name']    ?? '';
$email    = $_GET['email']   ?? '';
$plan     = $_GET['plan']    ?? '';
$tools    = $_GET['tools']   ?? [];
$hours    = $_GET['hours']   ?? '';
$daytime  = $_GET['time']    ?? '';
$strategy = $_GET['strategy']?? '';

$errors = [];
if ($name === '')  $errors[] = "Name is required.";
if ($email === '') $errors[] = "Email is required.";
if ($plan === '')  $errors[] = "Please choose how you plan your study time.";

if ($errors){
  ?>
  <!DOCTYPE html><html lang="en"><head>
    <meta charset="utf-8"><title>Quiz – Missing info</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="my_style.css">
  </head><body>
    <?php include_once 'nav.php'; ?>
    <main class="body_wrapper">
      <h1>We need a bit more info</h1>
      <ul style="color:#b91c1c;font-weight:600;"><?php foreach($errors as $e){ echo "<li>".h($e)."</li>"; } ?></ul>
      <p><a href="my_form.php">Go back to the quiz</a></p>
    </main>
    <?php include_once 'footer.php'; ?>
  </body></html>
  <?php exit;
}

$score = 0;
if ($plan === 'daily')  $score += 30;
if ($plan === 'weekly') $score += 20;
if ($plan === 'wing-it')$score += 5;

$toolPoints = min(30, (is_array($tools) ? count($tools) : 0) * 10);
$score += $toolPoints;

$h = is_numeric($hours) ? max(0, min(24, (int)$hours)) : 0;
$score += (int) round(($h / 24) * 30);

switch ($daytime) {
  case 'morning':   $score += 10; break;
  case 'afternoon': $score += 8;  break;
  case 'evening':   $score += 6;  break;
  case 'late-night':$score += 4;  break;
}
$score = max(0, min(100, $score));
$cat = ($score >= 70) ? 'Highly Organized' : (($score >= 50) ? 'Steady Planner' : 'Finding Your Flow');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"><title>Your Quiz Results</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="my_style.css">
  <style>
    .results-wrap{max-width:900px;margin:24px auto;padding:0 16px;}
    .badge{display:inline-block;padding:4px 10px;border-radius:999px;font-weight:700;background:#111827;color:#fff;}
    .grid{display:grid;grid-template-columns:1fr;gap:16px;}
    @media(min-width:800px){ .grid{grid-template-columns:repeat(3,1fr);} }
    .card{border:1px solid #d6deee;border-radius:12px;padding:16px;background:#fff;}
    .highlight{outline:3px solid #10b981; box-shadow:0 0 0 4px rgba(16,185,129,.15);}
  </style>
</head>
<body>
  <?php include_once 'nav.php'; ?>
  <main class="body_wrapper">
    <div class="results-wrap">
      <h1>Thanks, <?= h($name) ?>!</h1>
      <p>Your score: <span class="badge"><?= $score ?>/100</span></p>
      <p><strong>Category:</strong> <?= h($cat) ?></p>

      <h2>What the categories mean</h2>
      <div class="grid">
        <section class="card <?= $cat==='Highly Organized' ? 'highlight':'' ?>">
          <h3>Highly Organized (70–100)</h3>
          <p>You plan ahead, use tools, and keep a consistent study rhythm.</p>
        </section>
        <section class="card <?= $cat==='Steady Planner' ? 'highlight':'' ?>">
          <h3>Steady Planner (50–69)</h3>
          <p>You’ve got a decent system. Add one extra tool or tighten a weekly plan to level up.</p>
        </section>
        <section class="card <?= $cat==='Finding Your Flow' ? 'highlight':'' ?>">
          <h3>Finding Your Flow (&lt;50)</h3>
          <p>Start small: pick a study window, track hours for a week, and try one simple tool.</p>
        </section>
      </div>

      <h2>Your inputs</h2>
      <ul>
        <li><strong>Email:</strong> <?= h($email) ?></li>
        <li><strong>Plan:</strong> <?= h($plan) ?></li>
        <li><strong>Tools:</strong> <?= h(is_array($tools)? implode(', ', $tools): '') ?></li>
        <li><strong>Hours/day:</strong> <?= h($hours) ?></li>
        <li><strong>When:</strong> <?= h($daytime) ?></li>
        <li><strong>Strategy:</strong> <?= nl2br(h($strategy)) ?></li>
      </ul>

      <p><a href="my_form.php">Take the quiz again</a></p>
    </div>
  </main>
  <?php include_once 'footer.php'; ?>
</body>
</html>
