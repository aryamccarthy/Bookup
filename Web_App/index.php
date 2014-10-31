<?php

mb_internal_encoding('UTF-8');
mb_http_input('UTF-8');
mb_http_output('UTF-8');

require_once("login/includes.php");

$login = new Login();

if ($login->isUserLoggedIn() == True) {

  header('Location: discovery.php');

} else {

  header('Location: login.php');
}
?>