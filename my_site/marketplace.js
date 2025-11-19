
const INVENTORY = [
  { id: 101, name: "Apple (1 lb bag)",     price: 3.49,   tags: ["apple","fruit","food","produce"], img: "images/apple.webp" },
  { id: 102, name: "City Bicycle",         price: 249.99, tags: ["bicycle","bike","transport","sport"], img: "images/bicycle.webp" },
  { id: 103, name: "Pet Grooming Brush",   price: 14.99,  tags: ["brush","pet","grooming","dog","cat"], img: "images/brush.jpg" },
  { id: 104, name: "Steel Hammer (16 oz)", price: 11.50,  tags: ["hammer","tool","hardware","steel"],  img: "images/hammer.png" }
];

document.addEventListener("DOMContentLoaded", () => {
  renderCards(INVENTORY);
  renderPlainList(INVENTORY);

  const q = document.getElementById('mp-q');
  document.getElementById('mp-btn').addEventListener('click', handleSearch);
  q.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); handleSearch(); } });

  const chipWords = ["apple", "bicycle", "brush", "hammer", "≤20"];
  const wrap = document.getElementById('mp-suggestions');
  wrap.querySelectorAll('.mp-chip').forEach(n => n.remove());
  chipWords.forEach(word => {
    const chip = document.createElement('span');
    chip.className = 'mp-chip';
    chip.dataset.q = word;
    chip.textContent = word;
    chip.style.cssText = "display:inline-block;margin:.25rem .35rem;padding:.25rem .5rem;border:1px solid #cfd7e6;border-radius:999px;cursor:pointer;background:#fff";
    chip.addEventListener('click', ()=>{ q.value = word; handleSearch(); });
    wrap.appendChild(chip);
  });
});

function handleSearch(){
  const qraw = (document.getElementById('mp-q').value || '').trim().toLowerCase();
  let data = [...INVENTORY];

  if (qraw){
    const m = qraw.match(/^(?:≤|<=|<)\s*(\d+(?:\.\d+)?)/);
    if (m){
      const cap = parseFloat(m[1]);
      data = data.filter(x => x.price <= cap);
    } else {
      data = data.filter(x =>
        x.name.toLowerCase().includes(qraw) ||
        x.tags.some(t => t.includes(qraw))
      );
    }
  }
  renderCards(data);
}

function renderCards(items){
  const grid = document.getElementById('mp-list');
  grid.innerHTML = '';
  if (!items.length){
    grid.innerHTML = '<p>No items match your search.</p>';
    return;
  }
  grid.style.cssText = "display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px;";

  items.forEach(it=>{
    const card = document.createElement('article');
    card.style.cssText = "border:1px solid #d6deee;border-radius:12px;overflow:hidden;background:#fff;box-shadow:0 4px 12px rgba(11,18,32,0.06)";
    const imgSafe = escapeHtml(it.name);
    card.innerHTML = `
      <img src="${it.img}" alt="${imgSafe}" style="width:100%;height:160px;object-fit:cover;"
           onerror="this.src='images/bird.png'; this.style.objectFit='contain';">
      <div style="padding:.75rem;">
        <h3 style="margin:.2rem 0 .3rem 0;font-size:1.05rem;">${escapeHtml(it.name)}</h3>
        <div style="opacity:.85;margin:.15rem 0;">$${it.price.toFixed(2)}</div>
        <div style="margin-top:.4rem;">
          ${it.tags.map(t=>`<span style="display:inline-block;margin:.15rem .25rem 0 0;padding:.15rem .45rem;border:1px solid #e5e7eb;border-radius:999px;font-size:.8rem">${escapeHtml(t)}</span>`).join('')}
        </div>
      </div>`;
    grid.appendChild(card);
  });
}

function renderPlainList(items){
  const ul = document.getElementById('mp-ul');
  ul.innerHTML = '';
  items.forEach(it=>{
    const li = document.createElement('li');
    li.textContent = `${it.name} — $${it.price.toFixed(2)} [${it.tags.join(', ')}]`;
    li.style.cursor = 'pointer';
    li.addEventListener('click', ()=>{
      document.getElementById('mp-q').value = it.tags[0] || it.name.split(' ')[0];
      handleSearch();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    ul.appendChild(li);
  });
}

function escapeHtml(s){
  return s.replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
}
