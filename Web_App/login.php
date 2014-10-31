<!DOCTYPE html>
<html lang="en">
<head>
  <?php require_once("html/head.html"); ?>
  
  <!-- Page-specific includes -->
  <link rel="stylesheet" href="css/login.css">
  <link rel="stylesheet" type="text/css" href="css/nolinks.css">
  <script type="text/javascript" src="js/login.js"></script>

  <title>Bookup | Login</title>
</head>
<body>
  <!-- <?php include_once(""); ?> -->
  <?php include_once("html/nav.html"); ?>
  <main>
    <h1 class="title">Bookup</h1>

    <form action="index.php" method="post" id="login" name="loginform">
      <ul>
        <li>Login/Sign Up</li>
        <li><hr></li>
        <li>
          <label for="email">Email</label>
          <input id="email" name="user_email" type="email" pattern=".{3,30}" placeholder="someone@example.com" autofocus required>
        </li>

        <li>
          <label for="password">Password</label>
          <input id="password" name="user_password" type="password" pattern = ".{7,30}" placeholder="••••••••••••" required>
        </li>

        <li>
          <input id="login_button" type="submit" name="login" class="twobutton" value="Login"/>
          <button id="register" class="twobutton">Sign Up</button>
        </li>
      </ul>

    </form>
  </main>
</body>
</html>