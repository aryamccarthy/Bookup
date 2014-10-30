<?php

require_once("login/includes.php");

$login = new Login();

if ($login->isUserLoggedIn() == True) {

  require_once('discovery.php');

} else {

  require_once('login.php');
}
?>