"use strict";

// This file powers the mini "marketplace" page.
// It keeps a small in-memory inventory, lets the user search by text or max price,
// and then rebuilds the product cards + simple text list based on that search.

// I keep the inventory in a constant array instead of hard-coding it in HTML,
// so I can reuse the same data both for the grid of cards and the plain <ul> list.
const INVENTORY = [
  { id: 101, name: "Apple (1 lb bag)",     price: 3.49,   tags: ["apple","fruit","food","produce"], img: "images/apple.webp" },
  { id: 102, name: "City Bicycle",         price: 249.99, tags: ["bicycle","bike","transport","sport"], img: "images/bicycle.webp" },
  { id: 103, name: "Pet Grooming Brush",   price: 14.99,  tags: ["brush","pet","grooming","dog","cat"], img: "images/brush.jpg" },
  { id: 104, name: "Steel Hammer (16 oz)", price: 11.50,  tags: ["hammer","tool","hardware","steel"],  img: "images/hammer.png" }
];

document.addEventListener("DOMContentLoaded", () => {
  // When the page loads, I render the full list once so the user
  // sees all products before searching.
  renderCards(INVENTORY);
  renderPlainList(INVENTORY);

  // This is the search box the user types into.
  const q = document.getElementById('mp-q');

  // The “Search” button just calls the same handler as pressing Enter in the input.
  document.getElementById('mp-btn').addEventListener('click', handleSearch);

  // Pressing Enter in the search box also triggers a search instead of submitting a form.
  q.addEventListener('keydown', e => {
    if (e.key === 'Enter') {
      e.preventDefault();
      handleSearch();
    }
  });

  // Here I pre-generate a few “chips” (quick filters) to show how tagging works.
  // They are just span elements that, when clicked, fill in the search box and run a search.
  const chipWords = ["apple", "bicycle", "brush", "hammer", "≤20"];
  const wrap = document.getElementById('mp-suggestions');

  // I clear any existing chips first so I don’t accidentally duplicate them on reload.
  wrap.querySelectorAll('.mp-chip').forEach(n => n.remove());

  chipWords.forEach(word => {
    const chip = document.createElement('span');
    chip.className = 'mp-chip';
    chip.dataset.q = word;
    chip.textContent = word;

    // I inline some basic styles here instead of making a separate CSS class,
    // just to keep the search example self-contained in one place.
    chip.style.cssText = "display:inline-block;margin:.25rem .35rem;padding:.25rem .5rem;border:1px solid #cfd7e6;border-radius:999px;cursor:pointer;background:#fff";

    chip.addEventListener('click', () => {
      q.value = word;
      handleSearch();
    });

    wrap.appendChild(chip);
  });
});

function handleSearch() {
  // I normalize the raw input by trimming spaces and forcing lowercase
  // so the matching is case-insensitive and doesn’t break on extra spaces.
  const qraw = (document.getElementById('mp-q').value || '').trim().toLowerCase();
  let data = [...INVENTORY]; // clone so I don’t mutate the original array

  if (qraw) {
    // I support a special "≤number" syntax to filter by max price.
    // This regex picks up values like "≤20", "<= 10.5", etc.
    const m = qraw.match(/^(?:≤|<=|<)\s*(\d+(?:\.\d+)?)/);
    if (m) {
      const cap = parseFloat(m[1]);
      data = data.filter(x => x.price <= cap);
    } else {
      // If it’s not a price query, I treat it as a text search:
      // either the product name or one of its tags must contain the search term.
      data = data.filter(x =>
        x.name.toLowerCase().includes(qraw) ||
        x.tags.some(t => t.includes(qraw))
      );
    }
  }

  // Once the data is filtered, I rebuild the grid display.
  renderCards(data);
}

function renderCards(items) {
  // This function is responsible for building the visual product cards grid.
  // I completely clear and rebuild the container so it always matches the current results.
  const grid = document.getElementById('mp-list');
  grid.innerHTML = '';

  if (!items.length) {
    grid.innerHTML = '<p>No items match your search.</p>';
    return;
  }

  // I set the grid layout via JS here to keep the “marketplace” logic in one place.
  grid.style.cssText = "display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px;";

  items.forEach(it => {
    const card = document.createElement('article');
    card.style.cssText = "border:1px solid #d6deee;border-radius:12px;overflow:hidden;background:#fff;box-shadow:0 4px 12px rgba(11,18,32,0.06)";

    const imgSafe = escapeHtml(it.name);

    // Each card shows an image, the product name, the price, and a few tag chips.
    // I also use onerror on the image to fall back to a default picture if the real one is missing.
    card.innerHTML = `
      <img src="${it.img}" alt="${imgSafe}" style="width:100%;height:160px;object-fit:cover;"
           onerror="this.src='images/bird.png'; this.style.objectFit='contain';">
      <div style="padding:.75rem;">
        <h3 style="margin:.2rem 0 .3rem 0;font-size:1.05rem;">${escapeHtml(it.name)}</h3>
        <div style="opacity:.85;margin:.15rem 0;">$${it.price.toFixed(2)}</div>
        <div style="margin-top:.4rem;">
          ${it.tags.map(t => `
            <span style="display:inline-block;margin:.15rem .25rem 0 0;padding:.15rem .45rem;border:1px solid #e5e7eb;border-radius:999px;font-size:.8rem">
              ${escapeHtml(t)}
            </span>`).join('')}
        </div>
      </div>`;

    grid.appendChild(card);
  });
}

function renderPlainList(items) {
  // The plain text <ul> is a second view of the same inventory.
  // Clicking on an item in this list triggers a search with that item’s first tag,
  // which makes it feel like a quick “filter by category” shortcut.
  const ul = document.getElementById('mp-ul');
  ul.innerHTML = '';

  items.forEach(it => {
    const li = document.createElement('li');
    li.textContent = `${it.name} — $${it.price.toFixed(2)} [${it.tags.join(', ')}]`;
    li.style.cursor = 'pointer';

    li.addEventListener('click', () => {
      document.getElementById('mp-q').value = it.tags[0] || it.name.split(' ')[0];
      handleSearch();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    ul.appendChild(li);
  });
}

function escapeHtml(s) {
  // I escape HTML manually so that product names or tags with special characters
  // don’t accidentally break my innerHTML or introduce XSS issues.
  return s.replace(/[&<>"']/g, m => ({
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#39;'
  }[m]));
}
