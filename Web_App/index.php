<?php 

require_once('sesh.php');

if($login->isUserLoggedIn()) {
  header('Location: discovery.php');
  exit();
} ?>