<!DOCTYPE html>
<html lang="en">
<head>
  <?php include("html/head.html"); ?>
  <link rel="stylesheet" type="text/css" href="css/nolinks.css">
  <!-- Page-specific includes -->
<script type="text/javascript">checkForSetup();</script>
  <title>Bookup</title>
</head>
<body>
  <?php include("html/nav.html"); ?>
  
  <section id="account_setup">
    <header>
      <h1>What have you read?</h1>
      <h2>Click on books you've read and give them a quick rating.</h2>
    </header>
    <div id="books_to_rate_region">
    <ol id="book_covers_to_rate"></ol>
    </div>
  <form action="discovery.php" method="POST">
    <input type="submit" value="Continue" id="setup_submit" class="cf" />
  </form>
  </section>

</body>
</html>