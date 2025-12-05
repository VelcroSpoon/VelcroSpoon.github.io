<?php
// This file essentially holds the helper functions for my blog so I don't need to
// repeat logic in blog.php, blog_new.php, and blog_delete.php.
// It pretty much centralizes where the blog data is stored (a JSON file), and
// exposes small "model" functions to load posts, save posts, and manage IDs.

/**
 * I keep config.php here in case I later want to reuse global settings
 * (like paths, permissions, or environment flags) inside these helpers.
 * Right now this file is independent, but this keeps the door open.
 */
require_once __DIR__ . '/config.php';

/**
 * Returns the full path to the JSON file that acts as my "database" for blog posts.
 *
 * I chose a single JSON file instead of a database because:
 * - the assignment doesn't require a DB,
 * - JSON is easy to inspect and edit directly if something breaks,
 * - and it keeps deployment simple (just a file next to my PHP code).
 *
 * The file lives one level above this includes/ folder so multiple pages
 * can share it without having to duplicate paths.
 */
function blog_data_file(): string {
  // On Osiris this ends up like: /srv/http/home/username/my_site/blog_data.json.
  // Keeping it outside the includes/ folder is just to separate code and data a bit.
  return __DIR__ . '/../blog_data.json';
}

/**
 * Load all blog posts from the JSON file into a PHP array.
 *
 * I treat this as the "read" part of my model:
 * - If the file doesn't exist yet, I just return an empty array so the blog
 *   page can render a "no posts yet" message without throwing errors.
 * - I also guard against invalid JSON by falling back to an empty array.
 *
 * @return array<int,array<string,mixed>>  List of posts, each post is an assoc array.
 */
function load_blog_posts(): array {
  $file = blog_data_file();

  if (!file_exists($file)) {
    // First-time run or manually deleted file: the blog is just empty instead of crashing.
    return [];
  }

  $json = file_get_contents($file);
  $data = json_decode($json ?? '[]', true);

  // If someone corrupts the JSON manually, this avoids fatal errors
  // and gives me a safe default instead.
  return is_array($data) ? $data : [];
}

/**
 * Save the given list of posts back into the JSON file.
 *
 * This is the "write" part of my model. Any time I create or delete a post,
 * the controllers (blog_new.php / blog_delete.php) call this function.
 * I also make sure the directory exists before writing, so the script doesn't
 * explode if the folder hasn't been created yet.
 *
 * @param array<int,array<string,mixed>> $posts  The entire blog post collection to persist.
 */
function save_blog_posts(array $posts): void {
  $file = blog_data_file();

  // In case the directory doesn't exist (for example on a fresh deploy),
  // I create it here so I don't have to do that check in every page.
  $dir = dirname($file);
  if (!is_dir($dir)) {
    @mkdir($dir, 0775, true);
  }

  // I use JSON_PRETTY_PRINT so the file stays readable if I open it on the server,
  // and JSON_UNESCAPED_UNICODE so non-ASCII characters (like French accents) look normal.
  file_put_contents(
    $file,
    json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
  );
}

/**
 * Compute the next numeric ID for a new post.
 *
 * Instead of tracking the "next id" somewhere else, I just scan the current
 * list of posts, find the maximum id, and return max+1. This keeps things
 * stateless: as long as I have the posts array, I can always get a new id.
 *
 * @param array<int,array<string,mixed>> $posts  Current list of posts.
 * @return int                                  The next available numeric id.
 */
function next_blog_id(array $posts): int {
  $max = 0;

  foreach ($posts as $post) {
    if (isset($post['id']) && is_numeric($post['id'])) {
      $value = (int)$post['id'];
      if ($value > $max) {
        $max = $value;
      }
    }
  }

  return $max + 1;
}

/**
 * Find the index of a post by its id inside the posts array.
 *
 * I use this in blog_delete.php to locate which array entry to unset.
 * Returning the index (instead of the post itself) makes it easy to
 * modify the original array (e.g., unset($posts[$index])) and then
 * pass it to save_blog_posts().
 *
 * I also cast both sides to string to avoid subtle bugs if some ids
 * are stored as strings and others as integers.
 *
 * @param array<int,array<string,mixed>> $posts  List of posts to search.
 * @param string|int $id                        The id of the post I'm looking for.
 * @return int|null                             Index in the array, or null if not found.
 */
function find_post_index_by_id(array $posts, $id): ?int {
  $id = (string)$id;

  foreach ($posts as $index => $post) {
    if (isset($post['id']) && (string)$post['id'] === $id) {
      return $index;
    }
  }

  return null;
}
