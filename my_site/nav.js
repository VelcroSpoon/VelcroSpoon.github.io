// --- helpers ---
function splitAtRoot(path) {
  const url = new URL(path, location.origin);
  return url.pathname; // e.g. "/home/sgrondin/index.php"
}

// Treat:
//   "/"                -> "/index.php"
//   "/home/sgrondin/"  -> "/home/sgrondin/index.php"
//   ".../index.html"   -> ".../index.php"
//   ".../index.php"    -> ".../index.php" (no change)
function normalizeIndex(p) {
  if (!p) return "/index.php";
  // directory path (ends with "/") => append index.php
  if (p.endsWith("/")) return p + "index.php";
  if (p.endsWith("/index.html")) return p.slice(0, -("index.html".length)) + "index.php";
  if (p.endsWith("/index.php")) return p; // already normalized
  return p;
}

// ---- required function with current_path argument ----
function setNav(current_path) {
  // d) normalize the current path you received
  current_path = normalizeIndex(splitAtRoot(current_path));

  // fetch nav.html and inject it
  fetch("nav.html")
    .then(r => r.text())
    .then(html => {
      const navEl = document.getElementById("main-nav");
      if (!navEl) return;
      navEl.innerHTML = html;

      // e) loop over nav items and add current_page when href matches
      const links = navEl.querySelectorAll("a[href]");
      links.forEach(a => {
        const target = normalizeIndex(splitAtRoot(a.href));
        if (target === current_path) {
          a.classList.add("current_page");
          a.setAttribute("aria-current", "page");
        }
      });
    })
    .catch(err => console.error("Failed to load nav.html:", err));
}
