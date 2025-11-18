<?php
// Guard against missing required GET fields
if (!isset($_GET['name']) || trim($_GET['name']) === '') {
  http_response_code(400);
  include_once __DIR__.'/nav.php';
  echo '<main class="body_wrapper"><h1>Form error</h1><p style="color:#b91c1c;">Missing required inputs. Please complete the form.</p><p><a href="/home/sgrondin/my_form.php">Go back to the quiz</a></p></main>';
  include_once __DIR__.'/footer.php';
  exit;
}

// Collect values (add more as needed)
$name   = trim($_GET['name']);
$email  = trim($_GET['email'] ?? '');
$plan   = $_GET['plan'] ?? '';              // radio
$hours  = isset($_GET['hours']) ? (int)$_GET['hours'] : 0;
$tools  = $_GET['tools'] ?? [];             // array of checkboxes
$time   = $_GET['time'] ?? '';
$strat  = trim($_GET['strategy'] ?? '');

// Simple scoring demo
$score = 0;
if ($plan === 'daily')  $score += 40;
if ($plan === 'weekly') $score += 25;
$score += max(0, min($hours, 8)) * 3;  // cap influence
$score += in_array('flashcards', (array)$tools, true) ? 10 : 0;
$score += in_array('pomodoro', (array)$tools, true)   ? 10 : 0;
$score = max(0, min($score, 100));

$cat = ($score >= 50) ? 'Organized Learner' : 'Explorer';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Quiz result for <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/home/sgrondin/my_style.css">
</head>
<body>
  <?php include_once __DIR__ . '/nav.php'; ?>

  <main class="body_wrapper">
    <h1>Thanks, <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>!</h1>
    <p>Your score: <strong><?= $score ?></strong>/100</p>

    <section style="display:grid;gap:12px;max-width:800px;">
      <article class="result-card" style="border:1px solid #d6deee;border-radius:10px;padding:12px;background:#fff;">
        <h2>Organized Learner</h2>
        <p>Plans ahead, tracks progress, and uses structured tools. Great at consistency and calm execution.</p>
        <?php if ($cat === 'Organized Learner'): ?>
          <p style="color:#065f46;background:#ecfdf5;border:1px solid #10b981;padding:8px;border-radius:8px;">✓ Your category</p>
        <?php endif; ?>
      </article>

      <article class="result-card" style="border:1px solid #d6deee;border-radius:10px;padding:12px;background:#fff;">
        <h2>Explorer</h2>
        <p>Learns by trying, tinkering, and discovering. High energy—benefits from light routines to channel focus.</p>
        <?php if ($cat === 'Explorer'): ?>
          <p style="color:#065f46;background:#ecfdf5;border:1px solid #10b981;padding:8px;border-radius:8px;">✓ Your category</p>
        <?php endif; ?>
      </article>
    </section>

    <p><a href="/home/sgrondin/my_form.php">Take the quiz again</a></p>
  </main>

  <?php include_once __DIR__ . '/footer.php'; ?>
</body>
</html>
