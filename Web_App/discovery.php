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
      <button id="add_to_list">Add to List</button>
      <button id="already_read">Already Read</button>
      <button id="next">Next</button>
    </fieldset>
    </div>
  </section>
  <section id="rate_from_discovery">
    <form>
      <h1>Help us get to know you and your preferences!</h1> 
      <h2> How would you rate this book?</h2>
      <button class="discoveryratingbook" id="likebutton" value="1">  <img src='img/like.png' alt='Like' /> </button>
      <button class="discoveryratingbook" id="dislikebutton" value="-1">  <img src='img/dislike.png' alt='Dislike' /> </button>
    </form>
  </section>
  </div>
<?php require_once("html/footer.html"); ?>
</body>
</html>
