<?php
// This file is the "delete endpoint" for my blog.
// Instead of reloading a whole page, my JavaScript calls this PHP script with fetch()
// when the user clicks the Delete button. It checks that the user is logged in,
// validates the post id, updates the JSON file, and then returns a tiny JSON response
// so the front-end can update the UI without a full page refresh.

// Turn on error display while I'm developing so it's easier to debug
// if something goes wrong with the AJAX request.
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/includes/config.php';        // Gives me session handling + is_logged_in()
require_once __DIR__ . '/includes/blog_functions.php';// Gives me load/save helpers + find_post_index_by_id()

// Tell the browser (and my JS) that this script always returns JSON.
// This keeps the contract clear: blog.js expects { ok: true/false, ... } from here.
header('Content-Type: application/json');

// First guard: only logged-in users are allowed to delete posts.
// This reuses the same login state as the rest of the site instead of
// trusting the front-end to hide the delete button.
if (!is_logged_in()) {
  http_response_code(403); // 403 = "Forbidden"
  echo json_encode([
    'ok'    => false,
    'error' => 'Not authorized.',
  ]);
  exit;
}

// Second guard: only accept POST requests for deleting.
// This avoids accidental deletes from someone just visiting the URL in the browser
// and also follows the common pattern "DELETE/updates should not be GET".
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405); // 405 = "Method Not Allowed"
  echo json_encode([
    'ok'    => false,
    'error' => 'Invalid request method.',
  ]);
  exit;
}

// Pull the post id out of the POST body.
// I cast to string + trim here to keep it simple, since the JSON file stores ids
// as integers but I compare them as strings in find_post_index_by_id().
$id = $_POST['id'] ?? '';
$id = trim((string)$id);

// If the front-end forgot to send an id, or it got lost, fail early
// instead of touching the blog data.
if ($id === '') {
  http_response_code(400); // 400 = "Bad Request"
  echo json_encode([
    'ok'    => false,
    'error' => 'Missing post id.',
  ]);
  exit;
}

// Load all posts from the JSON "database" and try to locate the one
// matching the id we got from the request.
$posts = load_blog_posts();
$index = find_post_index_by_id($posts, $id);

// If there is no post with that id, we return a clean error instead of
// silently doing nothing, so the UI can warn the user.
if ($index === null) {
  echo json_encode([
    'ok'    => false,
    'error' => 'Post not found.',
  ]);
  exit;
}

// At this point we have a valid index in the array.
// array_splice() removes exactly one element at that index and shifts the rest,
// so the JSON file stays a simple, zero-based array.
array_splice($posts, $index, 1);

// Save the updated posts array back into the JSON file so the delete
// persists across page reloads.
save_blog_posts($posts);

// Finally, tell the front-end everything went well.
// blog.js checks `ok: true` and then removes the post from the DOM.
echo json_encode([
  'ok' => true,
]);
