<!DOCTYPE html>
<html lang="en">
<head>
  <?php require_once("html/head.html"); ?>
  <script type="text/javascript">checkForDiscovery();</script>
  <title>Bookup | Discovery</title>
</head>
<body>
  <?php include("html/nav.html"); ?>
 <div id="content">
  <section id="discovery_section">
    <article id= "discovery_info">
      <h1 id="book_title">  </h1>
      <h2 id= "book_author"> </h2>
      <img id= "book_cover" src="">
      <p id= "book_description"> </p>
    </article>
    <div class="cf">
    <fieldset class="cf">
      <!--<button id="previous">Previous</button>-->
      <button id="add to list" onclick="addBookToReadingList()">Add to List</button>
      <button id="already_read" onclick="overlay('rate_from_discovery')">Already Read</button>
      <button id="next" onclick="">Next</button>
    </fieldset>
    </div>
  </section>
  <section id="rate_from_discovery">
    <form>
      <h1>Help us get to know you and your preferences!</h1> 
      <h2> How would you rate this book?</h2>
      <button class="discoveryratingbook" onclick="submitBookFeedback()" value="1">  <img src='img/like.png' alt='Like' /> </button>
      <button class="discoveryratingbook"onclick="submitBookFeedback()" value="-1">  <img src='img/dislike.png' alt='Dislike' /> </button>
    </form>
  </section>
  </div>
<?php require_once("html/footer.html"); ?>
</body>
</html>
