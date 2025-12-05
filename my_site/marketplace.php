<?php /* marketplace.php
       * This page is my small “fake marketplace” example for Lab 8–9.
       * I use static JavaScript data instead of a real database,
       * but I still treat it like a tiny product search page with a grid + list view.
       */ ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Marketplace</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- I keep all shared layout and component styling in my main CSS file
       so the marketplace inherits the same look as the rest of the site. -->
  <link rel="stylesheet" href="my_style.css">
</head>
<body>
  <?php
  // I reuse the same navigation bar on every page so the site feels coherent.
  // Including it here also keeps the active-page highlighting consistent.
  include_once 'nav.php';
  ?>

  <main class="body_wrapper mp-wrap">
    <h1>Marketplace</h1>

    <!-- This section is the “search bar” area:
         one text input, one search button, and a row of clickable suggestion chips. -->
    <section style="margin:12px 0 20px 0;">
      <!-- I keep the label for accessibility (screen readers) but visually hide it via CSS (sr-only). -->
      <label for="mp-q" class="sr-only">Search products</label>
      <div class="mp-row">
        <!-- The placeholder gives examples of both keyword search and price search (≤20). -->
        <input id="mp-q" class="mp-input" type="text"
               placeholder="Try: apple, brush, bicycle, hammer, or ≤20">
        <!-- This button triggers the JS search instead of submitting a form,
             because all filtering happens on the client side. -->
        <button id="mp-btn" type="button" class="mp-btn">Search</button>
      </div>
      <!-- The JS script dynamically fills this container with quick filter “chips”
           like apple / brush / ≤20 so the user can click instead of typing. -->
      <div id="mp-suggestions" style="margin-top:8px;"></div>
    </section>

    <!-- This section is where I render the main product cards grid
         (image, price, tags); the content is fully controlled by JavaScript. -->
    <section>
      <div id="mp-list" aria-live="polite"></div>
    </section>

    <!-- Below the grid, I show a plain text list of all items.
         Clicking one of these is a shortcut that fills the search box and scrolls up. -->
    <section style="margin-top:28px;">
      <h2 style="margin-bottom:8px;">Available items</h2>
      <ul id="mp-ul" style="padding-left:18px;line-height:1.75;"></ul>
    </section>
  </main>

  <?php
  // Shared footer so the bottom of every page has the same style and height.
  include_once 'footer.php';
  ?>

  <script>
  (function(){
    "use strict";

    // I treat this array as a tiny in-memory “database” of products.
    // Each item has an id (so it could be extended later), a display name, a price,
    // a list of tags for searching, and an image path for the card view.
    const INVENTORY = [
      { id: 101, name: "Apple (1 lb bag)",     price: 3.49,   tags: ["apple","fruit","food","produce"], img: "images/apple.webp" },
      { id: 102, name: "City Bicycle",         price: 249.99, tags: ["bicycle","bike","transport","sport"], img: "images/bicycle.webp" },
      { id: 103, name: "Pet Grooming Brush",   price: 14.99,  tags: ["brush","pet","grooming","dog","cat"], img: "images/brush.jpg" },
      { id: 104, name: "Steel Hammer (16 oz)", price: 11.50,  tags: ["hammer","tool","hardware","steel"],  img: "images/hammer.png" }
    ];

    // I cache all the main DOM elements once so I don’t keep calling getElementById everywhere.
    const $q   = document.getElementById('mp-q');
    const $btn = document.getElementById('mp-btn');
    const $sug = document.getElementById('mp-suggestions');
    const $grid= document.getElementById('mp-list');
    const $ul  = document.getElementById('mp-ul');

    // This helper escapes special characters so that product names / tags
    // can be safely inserted via innerHTML without breaking the markup.
    const esc = s => String(s).replace(/[&<>"']/g, m => ({
      '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'
    }[m]));

    // This function fills the bottom <ul> with a simple text version of the inventory.
    // Clicking an item in this list automatically sets the search term based on a tag,
    // which doubles as a quick way to “jump” to a category from the list view.
    function renderPlainList(items){
      $ul.innerHTML = '';
      items.forEach(it=>{
        const li = document.createElement('li');
        li.textContent = `${it.name} — $${it.price.toFixed(2)} [${it.tags.join(', ')}]`;
        li.style.cursor = 'pointer';
        li.addEventListener('click', ()=>{
          // When the user clicks a list item, I reuse its first tag as a search query
          // (or fall back to the first word of the name) and then re-run the filter.
          $q.value = it.tags[0] || it.name.split(' ')[0];
          handleSearch();
          window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        $ul.appendChild(li);
      });
    }

    // This function rebuilds the main grid of product cards.
    // I clear the container and re-inject a set of cards based on whatever list
    // is passed in, so it can respond to searches and filters.
    function renderCards(items){
      if (!items.length){
        // If nothing matches, I reset the layout and show a friendly message.
        $grid.className = '';
        $grid.innerHTML = '<p>No items match your search.</p>';
        return;
      }

      // I use a CSS class for the grid to keep layout styling in my main stylesheet.
      $grid.className = 'mp-grid';

      // Each card shows the product image, name, price, and visual tags.
      // I also add a simple onerror handler to fall back to a default image.
      $grid.innerHTML = items.map(it => `
        <article class="mp-card">
          <img src="${esc(it.img)}" alt="${esc(it.name)}"
               onerror="this.src='images/bird.png'; this.style.objectFit='contain';">
          <div style="padding:.75rem;">
            <h3>${esc(it.name)}</h3>
            <div style="opacity:.85;margin:.15rem 0;">$${it.price.toFixed(2)}</div>
            <div style="margin-top:.4rem;">
              ${it.tags.map(t=>`<span class="mp-tag">${esc(t)}</span>`).join('')}
            </div>
          </div>
        </article>
      `).join('');
    }

    // This is the main search handler.
    // It reads the current text input, decides if it’s a price filter (“≤20”)
    // or a keyword search, and then filters the INVENTORY array accordingly.
    function handleSearch(){
      const raw = ($q.value || '').trim().toLowerCase();
      let data = [...INVENTORY];

      if (raw){
        // I support a special syntax like “≤20” or “<= 15.5”,
        // which lets the user filter by maximum price instead of text.
        const m = raw.match(/^(?:≤|<=|<)\s*(\d+(?:\.\d+)?)/);
        if (m){
          const cap = parseFloat(m[1]);
          data = data.filter(x => x.price <= cap);
        } else {
          // Otherwise, treat the query as a simple substring search
          // across both the product name and its tags.
          data = data.filter(x =>
            x.name.toLowerCase().includes(raw) ||
            x.tags.some(t => t.includes(raw))
          );
        }
      }

      renderCards(data);
    }

    // I build a row of “suggestion chips” on load to show possible queries
    // and encourage the user to try both keyword and price searches.
    (function buildChips(){
      const chipWords = ["apple", "bicycle", "brush", "hammer", "≤20"];
      $sug.innerHTML = '';
      chipWords.forEach(word=>{
        const chip = document.createElement('span');
        chip.className = 'mp-chip';
        chip.dataset.q = word;
        chip.textContent = word;
        chip.addEventListener('click', ()=>{
          $q.value = word;
          handleSearch();
        });
        $sug.appendChild(chip);
      });
    })();

    // The button simply calls the search handler so the user doesn’t need to hit Enter.
    $btn.addEventListener('click', handleSearch);

    // Pressing Enter inside the search input also triggers a search
    // instead of submitting a traditional HTTP form.
    $q.addEventListener('keydown', e => {
      if (e.key === 'Enter'){
        e.preventDefault();
        handleSearch();
      }
    });

    // On initial page load, I show the full inventory in both the grid and the list
    // so the user can see everything before narrowing it down.
    renderCards(INVENTORY);
    renderPlainList(INVENTORY);
  })();
  </script>
</body>
</html>
