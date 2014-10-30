<?php

require_once("login/includes.php");

$login = new Login();

  if ($login->isUserLoggedIn() == True) { //TODO: change condition
    header('Location: discovery.php');
  } else {
    header('Location: login.php');
  }
  ?>