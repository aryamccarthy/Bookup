<?php

$host = '54.69.55.132';
$user = 'test';
$pass = 'Candles';

try {
    $pdo = new PDO("mysql:host=$host;dbname=BookUp", "$user", "$pass");
    echo "success\n";
} 
catch (PDOException $e) {
    $response = "Failed to connect: ";
    $response .= $e->getMessage();
    die ($response);
}

?>