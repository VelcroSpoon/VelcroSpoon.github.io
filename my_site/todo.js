// I keep the full to-do list in localStorage so tasks survive page refreshes.
// On first load, I try to read the "items" key; if nothing is stored yet, I fall back to an empty array.
let items = JSON.parse(localStorage.getItem("items")) || [];

// As soon as the page is ready, I rebuild the list in the DOM from whatever is in `items`.
document.addEventListener("DOMContentLoaded", function () {
  renderList();
});

function addItem(event) {
  // I stop the form from doing a normal page submit because I want everything
  // to be handled via JavaScript and stay on the same page.
  event.preventDefault();

  const input = document.getElementById("todo-text");
  const text = input.value.trim();

  // Simple validation: I don’t want to add completely empty tasks to the list.
  if (text === "") {
    alert("Please write something first.");
    return;
  }

  // I give each item a unique id based on the current timestamp.
  // This makes it easy to remove the right object later and keeps the structure simple.
  const newItem = { text, id: Date.now() };
  items.push(newItem);

  // Every time the list changes, I overwrite the localStorage copy so it stays in sync.
  localStorage.setItem("items", JSON.stringify(items));

  // I only render the new item instead of rebuilding the whole list,
  // which is a bit more efficient and feels snappier.
  renderItem(newItem.text, newItem.id);

  // After adding, I clear the box and put the cursor back so the user can quickly type the next task.
  input.value = "";
  input.focus();
}

function renderList() {
  const ul = document.getElementById("todo-list");
  // I reset the list before re-drawing so I don’t end up with duplicates
  // when the page calls this after a reload.
  ul.innerHTML = "";
  items.forEach((item) => renderItem(item.text, item.id));
}

function renderItem(item_text, id) {
  const ul = document.getElementById("todo-list");

  // This <li> represents one task in the list. I store the item id in data-id
  // so I could find it later if I wanted to extend the feature.
  const li = document.createElement("li");
  li.className = "todo-item";
  li.dataset.id = id;

  // The text span holds the actual task description and uses a CSS class
  // so I can control wrapping and layout from my_style.css.
  const textSpan = document.createElement("span");
  textSpan.className = "todo-text";
  textSpan.textContent = item_text;

  // This span is just the trash icon. I assign both Font Awesome classes
  // and my own "todo-trash" class so I can style the delete button consistently.
  const trashSpan = document.createElement("span");
  trashSpan.classList.add("fas", "fa-trash", "todo-trash");

  // When the user clicks the trash icon, I remove the item from the DOM
  // and also filter it out from the `items` array before updating localStorage.
  // This keeps the visual list and the saved data perfectly aligned.
  trashSpan.addEventListener("click", function () {
    li.remove();
    items = items.filter((x) => x.id !== id);
    localStorage.setItem("items", JSON.stringify(items));
  });

  li.appendChild(textSpan);
  li.appendChild(trashSpan);
  ul.appendChild(li);
}
