<?php

require_once("login/includes.php");

$login = new Login();

  if ($login->isUserLoggedIn() == True) {
    include('./discovery.php');
  } else {
    include('./login.php');
  }
  ?>