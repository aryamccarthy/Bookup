<!DOCTYPE html>
<html lang="en">
<head>
  <?php require_once("html/head.html"); ?>
  <script type="text/javascript">checkForList();</script>
  <!-- Page-specific includes -->
  <title>Bookup | Reading List</title>
</head>
<body>
  <?php include("html/nav.html"); ?>
  <main>
    <div id="list_body">
    <section id="reading_list">
      <header>
        <h1>Reading List</h1>
      </header>
      <ol id="list_books">

      </ol>
    </section>

    <section id="this_book">
      <article id="list_book_info">
        <div>
          <h1 id="list_title"></h1>
          <h2 id= "list_author"></h2>
          <p id= "list_description"></p>
        </div>
        <img id= "list_cover" src=" ">
      </article>

      <button>Remove from list</button>
    </section>
</div>
  </main>
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
