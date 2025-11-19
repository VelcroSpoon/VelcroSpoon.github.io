/* marketplace.js — simple client-side marketplace with localStorage cart */

const ITEMS = [
  {
    id: "bc-print",
    title: "Moraine Lake Print",
    price: 24.99,
    img: "images/BritishColumbia.jpg",
    tags: ["canada","lake","mountains","print"]
  },
  {
    id: "colosseum-poster",
    title: "Colosseum Poster",
    price: 19.99,
    img: "images/Colosseum.jpg",
    tags: ["italy","rome","history","poster"]
  },
  {
    id: "jp-countryside",
    title: "Japan Countryside Print",
    price: 17.50,
    img: "images/countryside.webp",
    tags: ["japan","countryside","print"]
  },
  {
    id: "desert-sunset",
    title: "Desert Mountains Poster",
    price: 14.99,
    img: "images/desertmountains.jpg",
    tags: ["desert","sunset","poster"]
  },
  {
    id: "lavender-lake",
    title: "Lavender Lake Print",
    price: 21.00,
    img: "images/lavender.jpg",
    tags: ["flowers","lake","print"]
  },
  {
    id: "mont-saint-michel",
    title: "Mont-Saint-Michel Poster",
    price: 18.75,
    img: "images/Mont-Saint-Michel-Normandy.jpg",
    tags: ["france","poster","landmark"]
  },
  {
    id: "versailles-garden",
    title: "Versailles Gardens Print",
    price: 22.00,
    img: "images/Palace-of-Versailles.jpg",
    tags: ["france","palace","print"]
  }
];

const elMarket = document.getElementById('market');
const elEmpty  = document.getElementById('empty');
const elSearch = document.getElementById('search');
const elCartCount = document.getElementById('cart-count');

function getCart() {
  try { return JSON.parse(localStorage.getItem('cart') || "[]"); }
  catch { return []; }
}
function setCart(arr) {
  localStorage.setItem('cart', JSON.stringify(arr));
  updateCartCount();
}
function updateCartCount() {
  const n = getCart().reduce((sum, item) => sum + item.qty, 0);
  if (elCartCount) elCartCount.textContent = n;
}

// Render cards
function render(list) {
  elMarket.innerHTML = "";
  if (!list.length) {
    elEmpty.style.display = "block";
    return;
  }
  elEmpty.style.display = "none";

  for (const it of list) {
    const card = document.createElement('article');
    card.className = 'card';
    card.innerHTML = `
      <img src="${it.img}" alt="${it.title}">
      <div class="body">
        <h3>${it.title}</h3>
        <div class="price">$${it.price.toFixed(2)}</div>
      </div>
      <button type="button" data-id="${it.id}">Add to cart</button>
    `;
    card.querySelector('button').addEventListener('click', () => addToCart(it.id));
    elMarket.appendChild(card);
  }
}

function addToCart(id) {
  const cart = getCart();
  const row = cart.find(r => r.id === id);
  if (row) row.qty += 1;
  else cart.push({ id, qty: 1 });
  setCart(cart);
}

function filter(query) {
  const q = query.trim().toLowerCase();
  if (!q) return ITEMS;
  return ITEMS.filter(it =>
    it.title.toLowerCase().includes(q) ||
    it.tags.some(t => t.toLowerCase().includes(q))
  );
}

// Init
render(ITEMS);
updateCartCount();

if (elSearch) {
  elSearch.addEventListener('input', (e) => render(filter(e.target.value)));
}
