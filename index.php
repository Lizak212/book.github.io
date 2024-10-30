<html>
<head>
  <title>Library</title>
  <style>
    body {
      display: flex; 
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }
  </style>
</head>
  
<body>
  <h1>Library</h1>

  <form action = "index.php" method = "POST">
    <input type = "hidden" name = "action" value = "add">

    <label> Title: </label>
    <input type = "text" name = "title">

    <label> Author: </label>
    <input type = "text" name = "author">
  
    <label> Genre: </label>
    <input type = "text" name = "genre">
  
    <button> Add Book </button>
  </form>

  <?php

  $db = new SQLite3 ("books.db");

  //$db->exec ("DROP TABLE IF EXISTS book");
  $db->exec ("CREATE TABLE IF NOT EXISTS book (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT,
    author TEXT,
    genre TEXT 
  )");

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
  
    if ($action == "add") {
      $title = $_POST['title'];
      $author = $_POST['author'];
      $genre = $_POST['genre'];
  
      $db->exec ("INSERT INTO book (title, author, genre)
      VALUES ('$title', '$author', '$genre')");
    }
  
    if ($action == "delete") {
      $db->exec ("DELETE FROM book WHERE id = " . $_POST['id']);
    }
  
    if ($action == "edit") {
      $id = $_POST['id'];
      $title = $_POST['title'];
      $author = $_POST['author'];
      $genre = $_POST['genre'];
      
      $db->exec ("UPDATE book SET title = '$title', author = '$author', genre = '$genre' WHERE id = $id");
    }
  }

  $result = $db->query ("SELECT * FROM book");

  echo "<table>";
  echo "<tr>
  <th>ID</th>
  <th>Title</th>
  <th>Author</th>
  <th>Genre</th>
  <th>Action</th>
  </tr>";

  while ($row = $result->fetchArray (SQLITE3_ASSOC)) {
    echo "<tr>";
    echo "<td>" . $row ["id"] . "</td>";
    echo "<td>" . $row ["title"] . "</td>";
    echo "<td>" . $row ["author"] . "</td>";
    echo "<td>" . $row ["genre"] . "</td>";

    echo "<td>
      <form action = 'index.php' method = 'POST'>
      <input type = 'hidden' name = 'id' value ='" . $row['id'] . "'>
      <input type = 'hidden' name = 'action' value = 'delete'>
      <button> Delete </button>
      </form>
  
      <form action = 'index.php' method = 'POST'>
      <input type = 'hidden' name = 'id' value ='" . $row['id'] . "'>
      <input type = 'hidden' name = 'action' value = 'edit'>
      <input type = 'text' name = 'title' value ='" . $row['title'] . "'>
      <input type = 'text' name = 'author' value ='" . $row['author'] . "'> 
      <input type = 'text' name = 'genre' value ='" . $row['genre'] . "'> 
      <button> Edit </button>
      </form>
    </td>";
    echo "</tr>";
  }

  echo "</table>";
  $db->close();
  ?>
</body>
</html>
