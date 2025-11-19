<?php /* marketplace.php */ ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Marketplace</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="my_style.css">
  <style>
    /* page-local styles so it still looks good even if CSS cache is stale */
    .mp-wrap{max-width:1000px;margin:0 auto;}
    .mp-row{display:flex;gap:8px;align-items:center;flex-wrap:wrap;}
    .mp-input{flex:1 1 340px;padding:10px 12px;border:1px solid #cfd7e6;border-radius:10px;}
    .mp-btn{padding:10px 14px;border:0;border-radius:10px;background:#0b1220;color:#fff;font-weight:600;cursor:pointer;}
    .mp-chip{display:inline-block;margin:.25rem .35rem;padding:.25rem .5rem;border:1px solid #cfd7e6;border-radius:999px;cursor:pointer;background:#fff}
    .mp-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px;}
    .mp-card{border:1px solid #d6deee;border-radius:12px;overflow:hidden;background:#fff;box-shadow:0 4px 12px rgba(11,18,32,0.06)}
    .mp-card img{width:100%;height:160px;object-fit:cover;}
    .mp-card h3{margin:.2rem 0 .3rem 0;font-size:1.05rem;}
    .mp-tag{display:inline-block;margin:.15rem .25rem 0 0;padding:.15rem .45rem;border:1px solid #e5e7eb;border-radius:999px;font-size:.8rem}
  </style>
</head>
<body>
  <?php include_once 'nav.php'; ?>

  <main class="body_wrapper mp-wrap">
    <h1>Marketplace</h1>

    <section style="margin:12px 0 20px 0;">
      <label for="mp-q" class="sr-only">Search products</label>
      <div class="mp-row">
        <input id="mp-q" class="mp-input" type="text"
               placeholder="Try: apple, brush, bicycle, hammer, or ≤20">
        <button id="mp-btn" type="button" class="mp-btn">Search</button>
      </div>
      <div id="mp-suggestions" style="margin-top:8px;"></div>
    </section>

    <section>
      <div id="mp-list" aria-live="polite"></div>
    </section>

    <section style="margin-top:28px;">
      <h2 style="margin-bottom:8px;">Available items</h2>
      <ul id="mp-ul" style="padding-left:18px;line-height:1.75;"></ul>
    </section>
  </main>

  <?php include_once 'footer.php'; ?>

  <!-- Inline JS (removes any path/caching issues) -->
  <script>
  (function(){
    const INVENTORY = [
      { id: 101, name: "Apple (1 lb bag)",     price: 3.49,   tags: ["apple","fruit","food","produce"], img: "images/apple.webp" },
      { id: 102, name: "City Bicycle",         price: 249.99, tags: ["bicycle","bike","transport","sport"], img: "images/bicycle.webp" },
      { id: 103, name: "Pet Grooming Brush",   price: 14.99,  tags: ["brush","pet","grooming","dog","cat"], img: "images/brush.jpg" },
      { id: 104, name: "Steel Hammer (16 oz)", price: 11.50,  tags: ["hammer","tool","hardware","steel"],  img: "images/hammer.png" }
    ];

    const $q   = document.getElementById('mp-q');
    const $btn = document.getElementById('mp-btn');
    const $sug = document.getElementById('mp-suggestions');
    const $grid= document.getElementById('mp-list');
    const $ul  = document.getElementById('mp-ul');

    // Safe HTML
    const esc = s => String(s).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));

    function renderPlainList(items){
      $ul.innerHTML = '';
      items.forEach(it=>{
        const li = document.createElement('li');
        li.textContent = `${it.name} — $${it.price.toFixed(2)} [${it.tags.join(', ')}]`;
        li.style.cursor = 'pointer';
        li.addEventListener('click', ()=>{
          $q.value = it.tags[0] || it.name.split(' ')[0];
          handleSearch();
          window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        $ul.appendChild(li);
      });
    }

    function renderCards(items){
      if (!items.length){
        $grid.className = ''; // remove grid styles
        $grid.innerHTML = '<p>No items match your search.</p>';
        return;
      }
      $grid.className = 'mp-grid';
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

    function handleSearch(){
      const raw = ($q.value || '').trim().toLowerCase();
      let data = [...INVENTORY];

      if (raw){
        const m = raw.match(/^(?:≤|<=|<)\s*(\d+(?:\.\d+)?)/);
        if (m){
          const cap = parseFloat(m[1]);
          data = data.filter(x => x.price <= cap);
        } else {
          data = data.filter(x =>
            x.name.toLowerCase().includes(raw) ||
            x.tags.some(t => t.includes(raw))
          );
        }
      }
      renderCards(data);
    }

    // chips
    (function buildChips(){
      const chipWords = ["apple", "bicycle", "brush", "hammer", "≤20"];
      $sug.innerHTML = '';
      chipWords.forEach(word=>{
        const chip = document.createElement('span');
        chip.className = 'mp-chip';
        chip.dataset.q = word;
        chip.textContent = word;
        chip.addEventListener('click', ()=>{ $q.value = word; handleSearch(); });
        $sug.appendChild(chip);
      });
    })();

    // interactions
    $btn.addEventListener('click', handleSearch);
    $q.addEventListener('keydown', e => { if (e.key === 'Enter'){ e.preventDefault(); handleSearch(); }});

    // initial render
    renderCards(INVENTORY);
    renderPlainList(INVENTORY);
  })();
  </script>
</body>
</html>
