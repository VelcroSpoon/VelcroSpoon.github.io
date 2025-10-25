function splitAtRoot(path){
  const url = new URL(path, location.origin);
  const pathFromRoot = url.pathname;
  // debug
  console.log("path from root:", pathFromRoot);
  return pathFromRoot;
}

// ---- required function with current_path argument ----
function setNav(current_path){
  // d) normalize the current path you received
  current_path = splitAtRoot(current_path);

  // fetch nav.html and inject it
  fetch("nav.html")
    .then(r => r.text())
    .then(html => {
      document.getElementById("main-nav").innerHTML = html;

      // e) loop over nav items and add current_page when href matches
      const nav = document.getElementById("main-nav");

      // treat "/" and "/index.html" as the same page
      const normalizeIndex = (p) => (p === "/" ? "/index.html" : p);
      const cur = normalizeIndex(current_path);

      for (const child of nav.children){
        if (child instanceof HTMLAnchorElement){
          const target = splitAtRoot(child.href);
          const tar = normalizeIndex(target);
          if (tar === cur){
            child.classList.add("current_page");

          }
        }
      }
    });
}
