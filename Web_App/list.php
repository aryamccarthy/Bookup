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
      <h1 class="book_title"> Title </h1>
      <h1 class= "book_author"> Author</h1>
      <p class= "book_description"> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
      tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
      quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
      consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
      cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
      proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
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
