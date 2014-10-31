<!DOCTYPE html>
<html lang="en">
<head>
  <?php require_once("html/head.html"); ?>
  <script type="text/javascript">checkForDiscovery();</script>
  <title>Bookup | Discovery</title>
</head>
<body>
  <?php include("html/nav.html"); ?>

  <section id="discovery_section">
    <article id= "discovery_info">
      <h1 id="book_title">  </h1>
      <h2 id= "book_author"> </h2>
      <p id= "book_description"> </p>
      <img id= "book_cover" src="">
    </article>
    <div class="cf">
    <fieldset class="cf">
      <button id="previous">Previous</button>
      <button id="next" onclick="getBooks('getRandomBook');">Next</button>
      <button id="add to list" onclick="addBookToReadingList()">Add to List</button>
      <button id="already_read" onclick="overlay()">I've already read this book.</button>
    </fieldset>
    </div>
  </section>

  <dialog id="rate_from_discovery">
    <form>
      <h2>Help us get to know you and your preferences! <br> How would you rate this book?</h2>
      <!-- TODO: the values of these buttons will probably be values 0,1 ?? -->
      <button onclick="submitBookFeedback()" value="Like"> Like </button>
      <button onclick="submitBookFeedback()" value="Dislike"> Dislike </button>
    </form>
  </dialog>

</body>
</html>

