<?php
  if (isset($_SESSION['user'])) { //TODO: change condition
    header('Location: discovery.php');
  } else {
    header('Location: login.php');
  }
?>