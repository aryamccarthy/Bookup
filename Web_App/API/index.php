<?php

// Datbase information
// Put your stuff here
$host = 'localhost';
$user = 'root';
$pass = 'root';

require 'vendor/autoload.php';

// Get DB connection
$app = new \Slim\Slim();
try {
    $pdo = new PDO("mysql:host=$host;dbname=BurgerBar", "$user", "$pass");
} catch (PDOException $e) {
    $response = "Failed to connect: ";
    $response .= $e->getMessage();
    die ($response);
}

//
//	FUNCTTIONS GO BELOW HERE
//	Don't forget to import the global 
//	VVVVVVVVVVVVVVVVVVVVVVV

/**
*   Hello
*/
$app->get('/hello/:last/:first/:MI', function($last, $first, $MI) {
    echo "Hello, $first $MI. $last!";
});
$app->get('/hello', function() {
	echo "Hello. I don't know your name.";
});

/**
*	Get Popular Book
*
*	Owner: Nicole
*/
$app->get('/getPopularBooks', function() {
	global $pdo;

	$statement = $pdo->prepare(
		"SELECT * FROM PopularBook;");
	if ($statement->execute()){
		$books = array();

		while($row = $statement->fetch($fetch_style=$pdo::FETCH_ASSOC)){
			array_push($books, $row);
		}

		$result['Popular Books'] = $books;
	} else {
		$result['success'] = false;
		$result['error'] =$statement->errorInfo();
	}

	echo json_encode($result);
});

/**
*	Submit Setup Book Preferences
*
*	Owner: Nicole
*/
$app->post('/submitSetupBookPrefs', function() {
	global $pdo;

	$args[':userID'] = $_POST['User_idUser'];
	$args[':rating'] = $_POST['rating'];
	$args[':timestamp'] = $_POST['timestamp'];
	$args[':isbn'] = $_POST['Book_isbn'];

	$statement = $pdo->prepare(
		"INSERT INTO Rating(User_idUser, rating, timestamp, Book_isbn) VALUES 
		(:userID, :rating, :timestamp, :isbn);");
	if ($statement->execute($args)) {
		$result["success"] = true;
	} else {
		$result["success"] = false;
		$result["error"] = $statement->errorInfo();
	}
	
	echo json_encode($result);
});








//	^^^^^^^^^^^^^^^^^^^^^^^
//	FUNCTIONS GO ABOVE HERE
//


$app->run();


?>
