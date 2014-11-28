<?php

$host = '54.69.55.132';
$user = 'test';
$pass = 'Candles';
$dbname = 'BookUpv3';

function pullAuthorNames() {
    global $pdo;
    $statement = $pdo->prepare(
        "SELECT author FROM BookList_Good");
    if ($statement->execute()) {
        $auths = array();
        while ($row = $statement->fetch()) {
            array_push($auths, $row['author']);
        }
    }
    return $auths;
}

# get db connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname",
        "$user", "$pass");
} catch (PDOException $e) {
      $respone = "Failed to connect: ";
      $respone .= $e->getMessage();
      die ($response);
}

$auths = pullAuthorNames();
foreach ($auths as $auth) {
    echo $auth .\n';
}
