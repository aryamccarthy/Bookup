<!DOCTYPE html>
<html lang="en">
<head>
  <?php include("html/head.html"); ?>
  
  <!-- Page-specific includes -->
  <link rel="stylesheet" href="css/login.css">
  <link rel="stylesheet" type="text/css" href="css/nolinks.css">
  <script type="text/javascript" src="js/login.js"></script>

  <title>Bookup</title>
</head>
<body>
  <?php include("html/nav.html"); ?>
  <section id="main">
    <h1 class="title">Bookup</h1>

    <form action="login/index.php" method="post" id="loginform" name="loginform">
      <ul>
        <li>Login/Sign Up</li>
        <li><hr></li>
        <li>
          <label for="login_input_email">Email</label>
          <input id="login_input_email" name="user_email" type="email" pattern=".{3,30}" placeholder="someone@example.com" autofocus required>
        </li>

        <li>
          <label for="login_input_password">Password</label>
          <input id="login_input_password" type="user_password" pattern = ".{7,30}" placeholder="••••••••••••" required>
        </li>

        <li>
          <button id="register_user" class="twobutton">Sign Up</button>
          <input id="login_user" type="submit" name="login" class="twobutton" value="Login"/>
        </li>
      </ul>

    </form>
  </section>
</body>
</html>