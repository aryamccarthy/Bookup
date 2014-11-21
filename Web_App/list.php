<!DOCTYPE html>
<html lang="en">
<head>
  <?php require_once("html/head.html"); ?>
  <script type="text/javascript">checkForList();</script>
  <!-- Page-specific includes -->
  <script type="text/javascript">var switchTo5x=true;</script>
  <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
  <script type="text/javascript">stLight.options({publisher: "9f02dcc1-b04a-43b4-a328-e6c345332180", doNotHash: true, doNotCopy: true, hashAddressBar: true});</script>
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
        <button id="removebutton" >Remove from list</button>
        <div id="social" style="width:300px;height:32px;margin-left:auto;margin-right:auto;">
          <span class='st_facebook_large' displayText='Facebook'></span>
          <span class='st_twitter_large' displayText='Tweet'></span>
          <span class='st_googleplus_large' displayText='Google +'></span>
          <span class='st_pinterest_large' displayText='Pinterest'></span>
          <span class='st_email_large' displayText='Email'></span>
        </div>
      </section>
    </div>
  </main>
  <section id="delete_and_rate_from_list">
    <form>
      <h1>Did you read this book?</h1>
      <h2>Help us get to know you and your preferences! How would you rate this book?</h2>
      <button class="listratingbutton" id="likebutton" value="1"> <img src='img/like.png' alt='Like' /> </button>
      <button class="listratingbutton" id="dislikebutton" value="-1"> <img src='img/dislike.png' alt='Dislike' /> </button><br>
      <button class="listratingbutton" id="didntread" value="0">I didn't read this book.</button>
    </form>
  </section>
</body>
</html>