<!DOCTYPE html>
<html lang="en">
<head>
  <?php include("html/head.html"); ?>
  
  <!-- Page-specific includes -->
  <title>Bookup</title>
</head>
<body>
  <?php include("html/nav.html"); ?>

<section id="reading_list">
    <header>
      <h1>Reading List</h1>
    </header>
    <ol id="list_books">
      <li> Book 1</li>
      <li> Book 2</li>
    </ol>
    <article id="list_book_info">
      <h1 id="book_title"></h1>
      <h1 id= "book_author"></h1>
      <p id= "book_description"></p>
      <img id= "book_cover" src=" ">
    </article>

    <button>Remove from list</button>
  </section>

  <dialog id="delete_and_rate_from_list">
    <h1>Did you read this book?</h1>
    <h2>Help us get to know you and your preferences! How would you rate this book?</h2>
    <button>Like</button>
    <button>Dislike</button>
    <button>I didn't read this book.</button>
    <!-- TODO: Have some sort of confirmation that the book was deleted  -->
  </dialog>


</body>
</html>