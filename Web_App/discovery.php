<!DOCTYPE html>
<html lang="en">
<head>
  <?php include("html/head.html"); ?>
  
  <!-- Page-specific includes -->
  <link rel="stylesheet" href="css/login.css">
  <script type="text/javascript" src="js/master.js"></script>

  <title>Bookup</title>
</head>
<body>
  <?php include("html/nav.html"); ?>

  <section id="discovery_section">
    <article id= "discovery_info">
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

    <button id="previous">Previous</button>
    <button id="next">Next</button>
    <input type="button" onclick="addBookToReadingList()" value="Add to List">
    <button>I've already read this.</button>
  </section>

  <diaglog id="rate_from_discovery">
    <h2>Help us get to know you and your preferences! How would you rate this book?</h2>
    <input type="button" onclick="submitBookFeedback()" value="Like">
    <input type="button" onclick="submitBookFeedback()" value="Dislike">
  </dialog>

</body>
</html>

