<?php
mb_internal_encoding('UTF-8');
mb_http_input('UTF-8');
mb_http_output('UTF-8');
require_once("login/db.php");
require_once("login/Login.php");
$login = new Login();
if(!($login->isUserLoggedIn()))                 //if user is not logged in
  if($_SERVER['REQUEST_URI'] !== '/login.php')  //and isn't going to login.php
    header('Location: login.php');              //then send them to login.php
?>