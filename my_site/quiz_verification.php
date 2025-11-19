<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

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
  exit;
}


$name  = htmlspecialchars(trim($_GET['name']), ENT_QUOTES, 'UTF-8');
$plan  = $_GET['plan'] ?? 'wing-it';
$tools = $_GET['tools'] ?? [];
$hours = (int)($_GET['hours'] ?? 0);


$score = 0;
if ($plan === 'daily')  $score += 40;
if ($plan === 'weekly') $score += 25;
$score += min($hours * 5, 25);
$score += (is_array($tools) ? count($tools) : 0) * 5;
if ($score > 100) $score = 100;


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
    <h1>Hi <?= $name ?>, your result is:</h1>
    <p><strong>Score:</strong> <?= $score ?>/100</p>

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
