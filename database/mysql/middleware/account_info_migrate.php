<?php

$host = '54.69.55.132';
$user = 'test';
$pass = 'Candles';
$accounts = array();

try {
    $pdo1 = new PDO("mysql:host=$host;dbname=BookUpv3", "$user", "$pass");
} catch (PDOException $e) {
      $response = "Failed to connect: ";
      $respone .= $e->getMessage();
      die($response);
}

$get_accounts = $pdo1->prepare(
    "SELECT * FROM Account");

if ($get_accounts->execute()) {
    while ($row = $get_accounts->fetch()) {
        $account['email'] = $row['email'];
        $account['password'] = $row['password'];
        array_push($accounts, $account);
    }
}

try {
    $pdo2 = new PDO("mysql:host=$host;dbname=BookUpv4", "$user", "$pass");
} catch (PDOException $e) {
      $response = "Failed to connect: ";
      $response .= $e->getMessage();
      die($response);
}

$insert_accounts = $pdo2->prepare(
    "INSERT INTO Account (email, password) 
    VALUES (test, pass)"
);
#$insert_accounts->bindParam(1, $email);
#$insert_accounts->bindParam(2, $password);
#$email = 'test';
#$password = 'pass';
$insert_accounts->execute();

#foreach ($accounts as $account) {
#    $email = $account['email'];
#    $password = $account['password'];
#    $insert_accounts->execute();
#}

#$insert_accounts = $pdo2->prepare(
#    "INSERT INTO `Account`($cols) VALUES ($vals)");
#var_dump($accounts);

#if ($insert_accounts->execute()) {
#   "echo successfully insert values";
#}
?>
