<?php

// Datbase information
// Put your stuff here

include 'Firebase_Connections/firebaseIsbnLookup.php';
require 'vendor/autoload.php';

$host = '54.69.55.132';
$user = 'test';
$pass = 'Candles';

// Get DB connection
$app = new \Slim\Slim();
try {
    $pdo = new PDO("mysql:host=$host;dbname=BookUp", "$user", "$pass");
} 
catch (PDOException $e) {
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

/*
*	Get Popular Book
*
*	Owner: Nicole
*	Finished - Drizzuto
*/
$app->get('/getPopularBooks', function() {
	global $pdo;

	$firebaseObject = new FirebaseIsbnLookup();

	$statement = $pdo->prepare(
		"SELECT * FROM PopularBook;");

	if ($statement->execute()){
		$books = array();

		while($row = $statement->fetch($fetch_style=$pdo::FETCH_ASSOC)){
			//echo $row["isbn_num"];
			//echo "</br>";
			$bookObject = $firebaseObject->getBookJson($row["isbn_num"]);
			array_push($books, $bookObject);
			array_push($books, $row);
		}

		$result['Books'] = $books;
	} else {
		$result['success'] = false;
		$result['error'] =$statement->errorInfo();
	}

	echo json_encode($result);
});

/*
*	Get a random Book Object
*	Drizzuto
*	Finished - Drizzuto
*/

$app->get('/getRandomBook', function() {
	global $pdo;

	$firebaseObject = new FirebaseIsbnLookup();

	$statement = $pdo->prepare(
		"SELECT isbn_num FROM BookList
			ORDER BY RAND() LIMIT 1;");

	if ($statement->execute()) {
		$books = array();

		while($row = $statement->fetch($fetch_style=$pdo::FETCH_ASSOC))
		{
			echo $row["isbn_num"];
			$bookObject = $firebaseObject->getBookJson($row["isbn_num"]);
			array_push($books, $bookObject);
			array_push($books, $row);
		} 
		$result['Books'] = $books;
		$result['success'] = true;
	}
	else {
		$result["success"] = false;
		$result["error"] = $statement->errorInfo();
	}

	echo json_encode($result);

});

/*
*	Get Book From Firebase
*	Drizzuto
*	Finished - Drizzuto
*/

$app->get('/getBookFromFirebase', function() {
	global $pdo;

	$args[":isbn"] = $_GET["isbn"];

	$firebaseObject = new FirebaseIsbnLookup();

	$bookObject = $firebaseObject->getBookJson($args[":isbn"]);

	echo json_encode($bookObject);

});

/*
*	Get a Reading List for a User
*	Drizzuto
*	Finished - Drizzuto
*/

$app->get('/getReadingList', function() {
	global $pdo;

	$firebaseObject = new FirebaseIsbnLookup();

	$args [":email"] = $_GET['email'];

	$statement = $pdo->prepare(
						'SELECT isbn_num, timestamp FROM ReadingList
						WHERE email = :email ');

	if ($statement->execute($args)) {
		$books = array();

		while($row = $statement->fetch($fetch_style=$pdo::FETCH_ASSOC))
		{
			//echo $row["isbn_num"];
			$bookObject["book"] = $firebaseObject->getBookJson((string)$row["isbn_num"]);
			//var_dump($row);
			$bookObject["timestamp"] = $row["timestamp"];
			array_push($books, $bookObject);
			array_push($books, $row);
		} 
		$result['Books'] = $books;
		$result['success'] = true;
	}
	else {
		$result["success"] = false;
		$result["error"] = $statement->errorInfo();
	}

	echo json_encode($result);

});

/*
*	Add Book to Reading List
*	Drizzuto
*/

$app->post('/addBookToReadingList', function() {
	global $pdo;

	$args[":email"] = $_POST['email'];
	$args[":isbn"] = $_POST['isbn'];


	$statment = $pdo->prepare(
						'INSERT INTO ReadingList VALUES
						(:email, NOW(), :isbn);'
						);

	if ($statement->execute($args)) {
		$result["success"] = true;
	} else {
		$result["success"] = false;
		$result["error"] = $statement->errorInfo();
	}

	echo json_encode($result);

});

/*
*	Add Book to Reading List
*	Drizzuto
*	Finished
*/

$app->post('/submitBookFeedback', function() {
	global $pdo;

	$args[':email'] = $_POST['email'];
	$args[':rating'] = $_POST['rating'];
	$args[':isbn'] = $_POST['isbn'];

	$statement = $pdo->prepare(
		"INSERT INTO Rating(email, rating, timestamp, isbn_num) VALUES 
		(:email, :rating, NOW(), :isbn);");
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
