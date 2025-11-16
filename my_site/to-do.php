<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My To-Do List</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="my_style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
  <?php include_once 'nav.php'; ?>

  <main class="body_wrapper">
    <h1>To-Do List</h1>
    <p>Write something and click "Add to list". It stays even if you refresh.</p>

    <form id="todo-form" onsubmit="addItem(event)">
      <label class="todo-label" for="todo-text">New item</label>
      <div class="todo-input-row">
        <input type="text" id="todo-text" placeholder="ex: finish lab 8">
        <button type="submit">Add to list</button>
      </div>
    </form>

    <ul id="todo-list" class="todo-list"></ul>
  </main>

  <?php include_once 'footer.php'; ?>
  <script src="todo.js"></script>
</body>
</html>
