<!DOCTYPE html>
<html lang="en">
<head>
  <?php include("html/head.html"); ?>
  <link rel="stylesheet" href="css/login.css">
  <title>Test Page, Please Ignore</title>
</head>
<body>
  <?php include("html/nav.html"); ?>
  <div id="main">
    <h1 class="title">Bookup</h1>

    <form action="" method="POST" id="login">
      <ul>
        <li>Register Now</li>
        <li><hr></li>
        <li>
          <label for="email">Email</label>
          <input type="email" id="email" placeholder="someone@example.com">
        </li>

        <li>
          <label for="pass">Password</label>
          <input type="password" id="pass" placeholder="••••••••••••">
        </li>

        <li>
          <button id="register_user" class="twobutton">New User</button>
          <button id="submit_login" class="twobutton">Login</button>
        </li>
      </ul>

    </form>
  </div>
</body>
</html>