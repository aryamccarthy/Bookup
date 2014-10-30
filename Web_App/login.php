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

    <form action="" method="POST" id="login">
      <ul>
        <li>Register Now</li>
        <li><hr></li>
        <li>
          <label for="email">Email</label>
          <input type="email" id="email" pattern=".{3,30}" placeholder="someone@example.com">
        </li>

        <li>
          <label for="pass">Password</label>
          <input type="password" id="pass" pattern = ".{8,30}" placeholder="••••••••••••">
        </li>

        <li>
          <button id="register_user" class="twobutton">New User</button>
          <button id="login_user" class="twobutton">Login</button>
        </li>
      </ul>

    </form>
  </section>
</body>
</html>