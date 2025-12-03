let items = JSON.parse(localStorage.getItem("items")) || [];

document.addEventListener("DOMContentLoaded", function () {
  renderList();
});

function addItem(event) {
  event.preventDefault();

  const input = document.getElementById("todo-text");
  const text = input.value.trim();

  if (text === "") {
    alert("Please write something first.");
    return;
  }

  const newItem = { text, id: Date.now() };
  items.push(newItem);
  localStorage.setItem("items", JSON.stringify(items));

  renderItem(newItem.text, newItem.id);

  input.value = "";
  input.focus();
}

function renderList() {
  const ul = document.getElementById("todo-list");
  ul.innerHTML = ""; // avoid duplicates on refresh
  items.forEach((item) => renderItem(item.text, item.id));
}

function renderItem(item_text, id) {
  const ul = document.getElementById("todo-list");

  const li = document.createElement("li");
  li.className = "todo-item";
  li.dataset.id = id;

  const textSpan = document.createElement("span");
  textSpan.className = "todo-text";
  textSpan.textContent = item_text;

  const trashSpan = document.createElement("span");
  trashSpan.classList.add("fas", "fa-trash", "todo-trash");

  trashSpan.addEventListener("click", function () {
    li.remove();
    items = items.filter((x) => x.id !== id);
    localStorage.setItem("items", JSON.stringify(items));
  });

  li.appendChild(textSpan);
  li.appendChild(trashSpan);
  ul.appendChild(li);
}
