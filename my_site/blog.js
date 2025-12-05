"use strict";

// This file handles all the “smart” behaviour for the blog page in one place.
// It lets me keep blog.php mostly as a simple PHP/HTML view, and push the
// interactive features (search, sort, collapse, delete) into JavaScript.
// Features:
// - Delete posts via AJAX (talks to blog_delete.php and updates the JSON).
// - Collapse/expand posts so the page doesn’t feel like a huge wall of text.
// - Live search on titles + content using the aside search box.
// - Sorting by date or title without reloading the page.

document.addEventListener("DOMContentLoaded", function () {
  // I grab the main container that holds all <article class="blog-post"> elements
  // and the <ul> in the aside. These act as the “source of truth” for the UI.
  const postContainer = document.querySelector(".blog-posts");
  const asideList = document.querySelector(".blog-aside ul");

  // I store a snapshot of all posts and all aside <li> entries when the page loads.
  // Later, I reuse these arrays to filter/sort without having to query the DOM too often.
  let allPosts = postContainer
    ? Array.from(postContainer.querySelectorAll(".blog-post"))
    : [];
  let allAsideItems = asideList
    ? Array.from(asideList.querySelectorAll("li[data-id]"))
    : [];

  // These node lists are the main “controls” the user interacts with.
  const deleteButtons = document.querySelectorAll(".blog-delete-button");
  const toggleButtons = document.querySelectorAll(".blog-toggle-button");
  const searchInput = document.getElementById("blog-search-input");
  const sortSelect = document.getElementById("blog-sort-select");

  // I keep this helper for dates separate so if I ever change the date format,
  // I can adjust it here. Right now I just return the string as-is because the
  // PHP is already giving me YYYY-MM-DD, which is easy to compare.
  function normalizeDate(str) {
    return str || "";
  }

  // This function is responsible for sorting posts by title or date,
  // based on the selected mode in the <select>. I work with data-attributes
  // (data-title, data-date) so the PHP doesn’t need to know about the JS logic.
  function sortPosts(posts, mode) {
    const items = posts.slice(); // copy so I don’t mutate the original array

    items.sort(function (a, b) {
      const titleA = (a.dataset.title || "").toLowerCase();
      const titleB = (b.dataset.title || "").toLowerCase();
      const dateA = normalizeDate(a.dataset.date || "");
      const dateB = normalizeDate(b.dataset.date || "");

      switch (mode) {
        case "title-asc":
          return titleA.localeCompare(titleB);
        case "title-desc":
          return titleB.localeCompare(titleA);
        case "date-asc":
          return dateA.localeCompare(dateB);
        case "date-desc":
        default:
          return dateB.localeCompare(dateA);
      }
    });

    return items;
  }

  // This function decides whether a given post matches the current search text.
  // I check both the post title (from data-title) and the text content of the body,
  // so the user can search by keywords that appear inside the paragraphs too.
  function matchesQuery(post, query) {
    if (!query) return true; // no search text = everything visible

    const q = query.toLowerCase();
    const title = (post.dataset.title || "").toLowerCase();
    const bodyText = (
      post.querySelector(".blog-post-body")?.textContent || ""
    ).toLowerCase();

    return title.includes(q) || bodyText.includes(q);
  }

  // This is the main “refresh” function for the blog UI.
  // Every time the user searches, sorts, or deletes a post, I run this to:
  // 1) Filter posts by the search query,
  // 2) Sort them according to the dropdown,
  // 3) Rebuild both the main post list and the aside index so they stay in sync.
  function applyFiltersAndSorting() {
    if (!postContainer || allPosts.length === 0) {
      return; // nothing to do if there are no posts at all
    }

    const query = searchInput ? searchInput.value.trim() : "";
    const sortMode = sortSelect ? sortSelect.value : "date-desc";

    // Step 1: filter posts based on search text.
    let visiblePosts = allPosts.filter(function (post) {
      return matchesQuery(post, query);
    });

    // Step 2: sort the filtered posts according to the chosen mode.
    visiblePosts = sortPosts(visiblePosts, sortMode);

    // Step 3: rebuild the main container so the DOM order matches the sorted array.
    postContainer.innerHTML = "";
    visiblePosts.forEach(function (post) {
      postContainer.appendChild(post);
    });

    // Step 4: mirror that same order in the aside list.
    if (asideList) {
      // I create a list of IDs for the currently visible posts (in order).
      const idsInOrder = visiblePosts.map(function (post) {
        return post.dataset.id;
      });

      const newLis = [];
      // For each post id, I find the matching <li> from the original aside list.
      idsInOrder.forEach(function (id) {
        const li = allAsideItems.find(function (item) {
          return item.dataset.id === id;
        });
        if (li) {
          newLis.push(li);
        }
      });

      // Now I completely rebuild the <ul> so it reflects the filtered/sorted posts.
      asideList.innerHTML = "";
      if (newLis.length === 0) {
        // If nothing matches, I show a friendly message instead of an empty list.
        const li = document.createElement("li");
        li.textContent = "No posts match your search.";
        asideList.appendChild(li);
      } else {
        newLis.forEach(function (li) {
          asideList.appendChild(li);
        });
      }
    }
  }

  // --- Search: live filtering ---
  // I wire the search box so every keystroke immediately re-applies filters and sorting.
  // This feels more “app-like” and doesn’t require a full page reload.
  if (searchInput) {
    searchInput.addEventListener("input", applyFiltersAndSorting);
  }

  // --- Sorting: dropdown change ---
  // Any time the user changes the sort mode (newest, oldest, A–Z, etc.),
  // I reuse the same applyFiltersAndSorting() logic so everything stays consistent.
  if (sortSelect) {
    sortSelect.addEventListener("change", applyFiltersAndSorting);
  }

  // --- Collapsible posts ---
  // To stop the blog from turning into one huge vertical scroll,
  // I let users collapse and expand posts. By default, only the first post
  // is fully open and the rest start collapsed.
  toggleButtons.forEach(function (button, index) {
    const article = button.closest(".blog-post");
    if (!article) return;

    // Collapse all except the first one by default so the page feels lighter on first load.
    if (index !== 0) {
      article.classList.add("collapsed");
      button.textContent = "Show post";
      button.setAttribute("aria-expanded", "false");
    } else {
      button.textContent = "Hide post";
      button.setAttribute("aria-expanded", "true");
    }

    // Clicking the button just toggles a CSS class and updates the label/ARIA state.
    button.addEventListener("click", function () {
      const isCollapsed = article.classList.toggle("collapsed");
      button.textContent = isCollapsed ? "Show post" : "Hide post";
      button.setAttribute("aria-expanded", isCollapsed ? "false" : "true");
    });
  });

  // --- Delete logic (works together with search/sort) ---
  // This block connects the Delete buttons to blog_delete.php using fetch().
  // I chose AJAX instead of a full page reload so the UI feels smoother and
  // I can update both the post list and the aside index on the fly.
  deleteButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      const postId = button.getAttribute("data-post-id");
      if (!postId) return;

      // I always ask for confirmation so it’s harder to delete posts by accident.
      const ok = window.confirm("Are you sure you want to delete this post?");
      if (!ok) {
        return;
      }

      const article = document.getElementById("post-" + postId);

      // I send the id to blog_delete.php as x-www-form-urlencoded.
      // This way I stay consistent with traditional form posts and don’t
      // have to switch to JSON on the PHP side.
      fetch("blog_delete.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "id=" + encodeURIComponent(postId)
      })
        .then(function (response) {
          // I try to parse JSON if the server tells me it's JSON.
          // If not, I just fall back to treating a successful status as ok=true.
          const contentType = response.headers.get("Content-Type") || "";
          if (contentType.includes("application/json")) {
            return response.json();
          }
          if (!response.ok) {
            throw new Error("Server error: " + response.status);
          }
          return { ok: true };
        })
        .then(function (data) {
          if (data && data.ok) {
            // Remove the post <article> from the DOM.
            if (article && article.parentElement) {
              article.parentElement.removeChild(article);
            }

            // Also remove it from the in-memory array so future searches/sorts
            // don’t accidentally bring back a deleted post.
            allPosts = allPosts.filter(function (p) {
              return p !== article;
            });

            // Then I clean up the matching <li> in the aside index.
            if (asideList) {
              const li = asideList.querySelector(
                'li[data-id="' + postId + '"]'
              );
              if (li && li.parentElement) {
                li.parentElement.removeChild(li);
              }
              allAsideItems = allAsideItems.filter(function (item) {
                return item.dataset.id !== postId;
              });
            }

            // If we just deleted the last post, I replace the whole section
            // with a simple message + reset the aside.
            if (allPosts.length === 0 && postContainer) {
              postContainer.innerHTML =
                "<p>There are no blog posts yet. Log in and create one!</p>";
              if (asideList) {
                asideList.innerHTML = "<li>No posts yet.</li>";
              }
              return;
            }

            // Otherwise, I re-apply filters and sorting so the remaining posts
            // are still in the right order and the aside stays in sync.
            applyFiltersAndSorting();
          } else {
            const msg =
              data && data.error
                ? data.error
                : "Could not delete the post.";
            window.alert(msg);
          }
        })
        .catch(function (error) {
          console.error(error);
          window.alert("An error occurred while deleting the post.");
        });
    });
  });

  // On first load, I call this once so the default view matches
  // the “Newest first” option and the aside order.
  applyFiltersAndSorting();
});
