<?php require_once __DIR__ . '/config.php'; ?>
<?php
// Guard against missing required inputs (teacher note)
if (!isset($_GET['name']) || trim($_GET['name']) === '' || !isset($_GET['plan'])) {
  http_response_code(400);
  include __DIR__ . '/nav.php';
  echo '<main class="body_wrapper"><h1>Form error</h1><p style="color:#b91c1c;">Missing required inputs. Please complete the form.</p><p><a href="' .
       BASE_URL . 'my_form.php">Go back to the quiz</a></p></main>';
  include __DIR__ . '/footer.php';
  exit;
}

// --- Example scoring logic (keep/modify yours) ---
$name  = htmlspecialchars($_GET['name'], ENT_QUOTES, 'UTF-8');
$plan  = $_GET['plan'] ?? '';
$tools = $_GET['tools'] ?? []; // array if checkbox
$hours = (int) ($_GET['hours'] ?? 0);

$score = 0;
$score += ($plan === 'daily') ? 40 : (($plan === 'weekly') ? 25 : 10);
$score += is_array($tools) ? min(count($tools) * 8, 24) : 0;
$score += max(min($hours, 6), 0) * 6; // up to 36
if ($score > 100) $score = 100;

$category = ($score >= 50) ? 'Organized' : 'Still finding your rhythm';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Quiz Results</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?= BASE_URL ?>my_style.css">
</head>
<body>
  <?php include __DIR__ . '/nav.php'; ?>

  <main class="body_wrapper">
    <h1>Hi <?= $name ?>, here are your results</h1>
    <p>Your score: <strong><?= $score ?> / 100</strong></p>

    <h2>Categories</h2>
    <div style="display:grid;gap:12px;max-width:800px;">
      <div style="border:2px solid <?= ($category==='Organized'?'#16a34a':'#e5e7eb') ?>;border-radius:10px;padding:12px;">
        <strong>Organized (≥ 50)</strong>
        <p>You plan ahead, use tools, and keep consistent hours. Keep it up!</p>
      </div>
      <div style="border:2px solid <?= ($category==='Still finding your rhythm'?'#16a34a':'#e5e7eb') ?>;border-radius:10px;padding:12px;">
        <strong>Still finding your rhythm (&lt; 50)</strong>
        <p>Try a simple daily plan and one tool (flashcards or Pomodoro) to build momentum.</p>
      </div>
    </div>
  </main>

  <?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
