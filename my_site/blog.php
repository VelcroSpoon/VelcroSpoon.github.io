<?php
// This file is the main “view” for my blog.
// It pulls all posts from the JSON file via helper functions and then focuses
// mostly on outputting HTML. All the interactive behaviour (search/sort/delete)
// is handled separately in blog.js so this page doesn’t turn into a giant mess.

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/blog_functions.php';

// I load all existing blog posts once here in PHP. After this point,
// $posts is the single source of truth for the initial HTML output.
$posts = load_blog_posts();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Blog – Simon Grondin</title>
  <meta name="author" content="Simon Grondin">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- I’m keeping Tailwind here just for some quick utility classes
       (mainly for the hero header) instead of hand-writing every tiny style. -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- All custom layout/styling for the site lives in this one CSS file now. -->
  <link rel="stylesheet" href="my_style.css">
</head>
<body>
  <?php
  // I include the nav here so the blog page feels like part of the same site
  // as the rest of the pages (Home, Marketplace, Quiz, etc.).
  include_once __DIR__ . '/nav.php';
  ?>

  <main class="body_wrapper">
    <div class="blog-page">
      <!-- Blog hero section: visually separates the blog from the rest of the site.
           I’m mixing Tailwind utilities with my own CSS class for the animation. -->
      <header class="blog-header bg-slate-900 text-slate-100 rounded-2xl px-6 py-5 mb-6 shadow-lg blog-hero-animated">
        <div class="blog-hero">
          <h1 class="text-2xl font-semibold">Simon&#39;s CS &amp; Life Blog</h1>
          <p class="text-sm text-slate-200 mt-1">
            Thoughts about university, web development, and projects.
            All posts are loaded from a JSON file and rendered with PHP.
          </p>
        </div>

        <!-- This little “brainbulb” avatar + hover text is mostly to make the page
             feel less dry and also to show off the hover animation I added in CSS. -->
        <div class="blog-login flex items-center gap-3">
          <div class="flex flex-col items-center gap-1">
            <img
              src="images/brainbulb.png"
              alt="Brain and lightbulb icon"
              class="blog-hero-img-pulse w-12 h-12 rounded-full border border-slate-500 bg-slate-800 object-cover"
            />
            <span class="text-[0.7rem] text-slate-200">
              Hover me to see me glow ✨
            </span>
          </div>

          <?php if (is_logged_in()): ?>
            <!-- If the user is logged in, I show a small logout form here
                 so they can exit their session directly from the blog header. -->
            <form method="post" action="login.php">
              <button type="submit" name="logout" value="1">Logout</button>
            </form>
          <?php else: ?>
            <!-- If not logged in, I just give them a shortcut to the login page.
                 This is needed for adding new posts or using the to-do list. -->
            <a href="login.php">Login</a>
          <?php endif; ?>
        </div>
      </header>

      <!-- Main blog layout: two columns.
           Left: actual posts. Right: search/sort controls + clickable index. -->
      <section class="blog-layout-grid">
        <!-- Main column: posts -->
        <section class="blog-posts" aria-label="Blog posts">
          <?php if (empty($posts)): ?>
            <!-- If there are no posts yet, I show a simple message instead of an empty section. -->
            <p>There are no blog posts yet. Log in and create one!</p>
          <?php else: ?>
            <?php foreach ($posts as $post): ?>
              <?php
                // I sanitize each field before output to avoid XSS and handle missing values.
                $id    = htmlspecialchars((string)($post['id'] ?? ''), ENT_QUOTES, 'UTF-8');
                $title = htmlspecialchars($post['title'] ?? 'Untitled post', ENT_QUOTES, 'UTF-8');
                $date  = htmlspecialchars($post['date'] ?? '', ENT_QUOTES, 'UTF-8');
                $paragraphs = [];

                // Paragraphs are stored as an array in JSON so I can loop over them here.
                if (isset($post['paragraphs']) && is_array($post['paragraphs'])) {
                  $paragraphs = $post['paragraphs'];
                }
              ?>
              <!-- Each post is a separate <article> with data-* attributes.
                   These data attributes are used heavily by blog.js for search, sort, and delete. -->
              <article
                class="blog-post"
                id="post-<?= $id ?>"
                data-id="<?= $id ?>"
                data-title="<?= $title ?>"
                data-date="<?= $date ?>"
              >
                <header class="blog-post-header">
                  <h2><?= $title ?></h2>
                  <?php if ($date): ?>
                    <!-- I only show the date if one is present, so older posts without a date
                         wouldn’t break the layout. -->
                    <p class="blog-post-meta"><?= $date ?></p>
                  <?php endif; ?>
                </header>

                <div class="blog-post-body">
                  <?php foreach ($paragraphs as $p): ?>
                    <?php
                      // I re-escape each paragraph and run nl2br so that any line breaks
                      // typed into the form still show up in the browser.
                      $safeParagraph = nl2br(
                        htmlspecialchars((string)$p, ENT_QUOTES, 'UTF-8')
                      );
                    ?>
                    <p><?= $safeParagraph ?></p>
                  <?php endforeach; ?>
                </div>

                <div class="blog-post-footer">
                  <!-- This button is always visible and is wired up in blog.js
                       to collapse/expand the post body. ARIA attribute tracks state. -->
                  <button
                    type="button"
                    class="blog-toggle-button"
                    aria-expanded="true"
                  >
                    Hide post
                  </button>

                  <?php if (is_logged_in()): ?>
                    <!-- Only logged-in users can delete posts.
                         blog.js grabs data-post-id from here and sends it to blog_delete.php. -->
                    <button
                      type="button"
                      class="blog-delete-button"
                      data-post-id="<?= $id ?>"
                    >
                      Delete
                    </button>
                  <?php endif; ?>
                </div>
              </article>
            <?php endforeach; ?>
          <?php endif; ?>
        </section>

        <!-- Aside: index + controls -->
        <aside class="blog-aside" aria-label="Blog index">
          <h2>Posts</h2>

          <!-- These controls (search + sort) are purely UI here.
               All the behaviour is in blog.js so the PHP doesn't care how the user filters. -->
          <div class="blog-controls">
            <div class="blog-search">
              <label for="blog-search-input">Search posts</label>
              <input
                type="search"
                id="blog-search-input"
                placeholder="Search by title or content"
              >
            </div>

            <div class="blog-sort">
              <label for="blog-sort-select">Sort posts</label>
              <select id="blog-sort-select">
                <option value="date-desc">Newest first</option>
                <option value="date-asc">Oldest first</option>
                <option value="title-asc">Title A–Z</option>
                <option value="title-desc">Title Z–A</option>
              </select>
            </div>
          </div>

          <!-- This <ul> acts as a clickable index of posts.
               Each <li> mirrors the same data-* attributes and id as the main articles,
               so blog.js can re-order or hide them in sync with the main column. -->
          <ul>
            <?php if (empty($posts)): ?>
              <li>No posts yet.</li>
            <?php else: ?>
              <?php foreach ($posts as $post): ?>
                <?php
                  $id    = htmlspecialchars((string)($post['id'] ?? ''), ENT_QUOTES, 'UTF-8');
                  $title = htmlspecialchars($post['title'] ?? 'Untitled post', ENT_QUOTES, 'UTF-8');
                  $date  = htmlspecialchars($post['date'] ?? '', ENT_QUOTES, 'UTF-8');
                ?>
                <li
                  data-id="<?= $id ?>"
                  data-title="<?= $title ?>"
                  data-date="<?= $date ?>"
                >
                  <!-- The href anchors directly to the matching <article> via its id,
                       so even without JavaScript the index still works as navigation. -->
                  <a href="#post-<?= $id ?>"><?= $title ?></a>
                </li>
              <?php endforeach; ?>
            <?php endif; ?>
          </ul>

          <?php if (is_logged_in()): ?>
            <!-- Shortcut to the “add new post” page. This only shows up when logged in,
                 to match the requirement that only authenticated users can create posts. -->
            <div class="blog-new-post-link">
              <a href="blog_new.php">Add new post</a>
            </div>
          <?php endif; ?>
        </aside>
      </section>
    </div>
  </main>

  <?php
  // Shared footer so the blog page keeps the same look as every other page.
  include_once __DIR__ . '/footer.php';
  ?>

  <!-- All interactive behaviour for the blog (search, sort, collapse, delete)
       lives in this separate JS file to keep the PHP/HTML cleaner. -->
  <script src="blog.js" defer></script>
</body>
</html>
