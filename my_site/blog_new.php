<?php
// This page is the "create new blog post" screen for my site.
// It does two jobs in one file:
// - If the user visits it with GET, it shows the form to create a post.
// - If the user submits the form with POST, it validates the data,
//   converts the content into paragraphs, saves it into the JSON "database",
//   and then redirects back to blog.php so they see the updated list.

// While I'm developing, I want PHP errors to show up in the browser,
// especially for form handling bugs, so I turn on error display here.
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/includes/config.php';         // Handles sessions + is_logged_in(), shared across the app.
require_once __DIR__ . '/includes/blog_functions.php'; // All the JSON file helpers live here (load / save / next id).

// Only logged-in users should be able to add new posts.
// I reuse the same login check the to-do list and other protected pages use,
// and if you're not logged in, I push you to login.php first.
if (!is_logged_in()) {
  header('Location: login.php');
  exit;
}

// These variables hold the current form state. I initialize them here
// so I can safely echo them back into the form if there's an error.
$err = '';
$title = '';
$date = date('Y-m-d'); // Default the date field to "today" so the user doesn't have to type it.
$content = '';
$tags = '';

// This block only runs when the user actually submits the form.
// I keep the GET vs POST logic at the top so it's clear how this page behaves.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Pull the raw input out of $_POST and trim it so I don't save random spaces.
  // Using the null coalescing operator (?? '') keeps things safe if a field is missing.
  $title   = trim($_POST['title'] ?? '');
  $date    = trim($_POST['date'] ?? '');
  $content = trim($_POST['content'] ?? '');
  $tags    = trim($_POST['tags'] ?? '');

  // Minimal validation: a blog post without a title or content doesn't make sense,
  // so I set an error message and later re-display the form with the previous values.
  if ($title === '' || $content === '') {
    $err = 'Please enter both a title and some content.';
  } else {
    // If the user leaves the date blank, I fall back to today's date.
    // This avoids saving empty dates, which would be annoying for sorting later.
    if ($date === '') {
      $date = date('Y-m-d');
    }

    // Here I convert the big textarea into an array of paragraphs.
    // The idea is: each non-empty line in the textarea becomes one paragraph in JSON.
    // This gives me a bit more flexibility when rendering the post in blog.php.
    $lines = preg_split("/\r\n|\n|\r/", $content);
    $paragraphs = [];
    foreach ($lines as $line) {
      $line = trim($line);
      if ($line !== '') {
        $paragraphs[] = $line;
      }
    }

    // Tags are optional and entered as a comma-separated string like:
    // "cs203, projects, university". Here I split on commas, trim each tag,
    // and ignore any empty pieces so the JSON stays clean.
    $tagsArray = [];
    if ($tags !== '') {
      foreach (explode(',', $tags) as $tag) {
        $tag = trim($tag);
        if ($tag !== '') {
          $tagsArray[] = $tag;
        }
      }
    }

    // At this point, the input is clean enough to save.
    // I load the existing posts from the JSON file so I can append the new one.
    $posts = load_blog_posts();

    // Build the new post as an associative array that matches the structure
    // expected by blog.php (id, title, date, paragraphs, optional tags).
    $newPost = [
      'id'         => next_blog_id($posts), // I ask the helper to pick the next free numeric id.
      'title'      => $title,
      'date'       => $date,
      'paragraphs' => $paragraphs,
    ];

    // Only add the "tags" key if the user actually entered some.
    // This keeps the JSON a bit cleaner and lets me treat tags as optional.
    if (!empty($tagsArray)) {
      $newPost['tags'] = $tagsArray;
    }

    // Append the new post to the list and write the full array back to disk.
    // This keeps the "model" (JSON file) and the UI in sync.
    $posts[] = $newPost;
    save_blog_posts($posts);

    // After a successful save, I redirect to blog.php instead of re-displaying
    // the form. This avoids duplicate submissions if the user refreshes the page.
    header('Location: blog.php');
    exit;
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Add new blog post</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- All the actual styles live in my_style.css so this page stays focused on structure and PHP logic. -->
  <link rel="stylesheet" href="my_style.css">
</head>
<body>
  <?php include_once __DIR__ . '/nav.php'; ?>

  <main class="body_wrapper">
    <section class="blog-new-wrapper">
      <h1>Add a new blog post</h1>

      <!-- If validation failed, I show the error message in a styled paragraph.
           htmlspecialchars() makes sure I never echo raw HTML from $err. -->
      <?php if ($err): ?>
        <p class="error"><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></p>
      <?php endif; ?>

      <!-- The form posts back to this same file (blog_new.php).
           I also pre-fill field values with the PHP variables so if something
           goes wrong, the user doesn't lose what they typed. -->
      <form method="post" action="blog_new.php">
        <p>
          <label for="title">Title</label><br>
          <input
            type="text"
            id="title"
            name="title"
            required
            value="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>"
          >
        </p>

        <p>
          <label for="date">Date</label><br>
          <input
            type="date"
            id="date"
            name="date"
            value="<?= htmlspecialchars($date, ENT_QUOTES, 'UTF-8') ?>"
          >
        </p>

        <p>
          <label for="content">Content</label><br>
          <!-- The assignment asked specifically for a <textarea>, so I use it here.
               I also echo the previous $content if there was an error, and wrap it in
               htmlspecialchars() to avoid breaking the HTML. -->
          <textarea
            id="content"
            name="content"
            required
          ><?= htmlspecialchars($content, ENT_QUOTES, 'UTF-8') ?></textarea>
        </p>

        <p>
          <label for="tags">Tags (optional, comma-separated)</label><br>
          <input
            type="text"
            id="tags"
            name="tags"
            placeholder="cs203, projects, university"
            value="<?= htmlspecialchars($tags, ENT_QUOTES, 'UTF-8') ?>"
          >
        </p>

        <p>
          <button type="submit">Save post</button>
        </p>
      </form>
    </section>
  </main>

  <?php include_once __DIR__ . '/footer.php'; ?>
</body>
</html>
