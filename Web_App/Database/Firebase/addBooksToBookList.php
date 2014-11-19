<?php

$host = '54.69.55.132';
$user = 'test';
$pass = 'Candles';

// Get DB connection

try {
    $pdo = new PDO("mysql:host=$host;dbname=BookUp", "$user", "$pass");
    echo "connected";
} 
catch (PDOException $e) {
    $response = "Failed to connect: ";
    $response .= $e->getMessage();
    die ($response);
}

$lines = file("Isbn_Txt_Files/list_of_Isbn_in_FB.txt", FILE_IGNORE_NEW_LINES);

foreach ($lines as $lineNum => $isbn)
{

	$statement = $pdo->prepare(
		'INSERT INTO BookList VALUES
		(:isbn)'
	);
	$statement->bindParam(':isbn', $isbn);

	if ($statement->execute()) {
		echo "success $isbn";
	} else {
		echo "fail $isbn";
		$errorData = $statement->errorInfo();
		echo $errorData[2];
	}
}

?>