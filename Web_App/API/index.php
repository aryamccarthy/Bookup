<?php

// Datbase information
// Put your stuff here

include 'Firebase_Connections/firebaseIsbnLookup.php';
require 'vendor/autoload.php';

$host = 'localhost';
$user = 'root';
$pass = 'root';

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

/**
*	Get Popular Book
*
*	Owner: Nicole
*	Finished - Zack Fout
*/
$app->get('/getPopularBooks', function() {
	global $pdo;
        $books = array();
        $pyExecBase = 'python ./FireBase_Connections/firebaseLookup.py ';

	$statement = $pdo->prepare("SELECT * FROM PopularBookList");

	if ($statement->execute()) {
            while($row = $statement->fetch()) {
                $pyExecCmd = $pyExecBase . $row['isbn_num'];
                $fbLookup = exec($pyExecCmd);
                array_push($books, $fbLookup);
	    }

            $result['Books'] = $books;
            $result['success'] = true;
        } else {
              $result['success'] = false;
	      $result['error'] =$statement->errorInfo();
	}
     
    echo json_encode($result);
});


/**
* Check if email already present.
* @author Arya McCarthy
* Finished - Arya McCarthy
*/

$app->get('/userExists', function() {
	global $pdo;

	$args [":email"] = $_GET['email'];

	$statement = $pdo->prepare(
		"SELECT COUNT(email) AS count FROM Account
			WHERE email = :email ");

	if ($statement->execute($args)) {
		$result["success"] = true;
		$row = $statement->fetch($fetch_style=$pdo::FETCH_ASSOC);
		$result['exists'] = $row['count'] != 0;
		$result['error'] = $result['exists'] ? 'The username is taken' : '';
	}
	else {
		$result["success"] = false;
		$result["error"] = $statement->errorInfo();
	}

	echo json_encode($result);

});

/**
* Check if user has rated any books.
* @author Arya McCarthy
* Finished - Arya McCarthy
*/

$app->get('/isNewUser', function() {
	global $pdo;

	$args [":email"] = $_GET['email'];

	$statement = $pdo->prepare(
		"SELECT COUNT(*) AS count FROM Rating
			WHERE email = :email ");

	if ($statement->execute($args)) {
		$result["success"] = true;
		$row = $statement->fetch($fetch_style=$pdo::FETCH_ASSOC);
		$result['newUser'] = $row['count'] == 0;
		#$result['error'] = $result['exists'] ? 'The username is taken' : '';
	}
	else {
		$result["success"] = false;
		$result["error"] = $statement->errorInfo();
	}

	echo json_encode($result);

});

/**
* Add user to database.
* @author Arya McCarthy
* Finished - Arya McCarthy
*/

$app->post('/addUser', function() {
	global $pdo;

	$args [":email"] = $_POST['email'];
	$args [":password"] = $_POST['password'];

	$statement = $pdo->prepare(
		"INSERT INTO Account (email, password)
		VALUES (:email, :password) ");

	if ($statement->execute($args)) {
		$result['success'] = true;
	}
	else {
		$result['success'] = false;
		$result['error'] = $statement->errorInfo();
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
        $books = array();
        $pyExecBase = 'python ./FireBase_Connections/firebaseLookup.py ';

	$statement = $pdo->prepare(
		'SELECT isbn_num FROM BookList
		ORDER BY RAND() LIMIT 1;');

	if ($statement->execute()) {
		$books = array();

		while($row = $statement->fetch())
		{
                        $pyExecCmd = $pyExecBase . $row['isbn_num'];
                        $fbLookup = exec($pyExecCmd);
			array_push($books, $fbLookup);
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

$app->get('/getBookFromFirebase/:isbn', function($isbn) {
	global $pdo;
        $books = array();
        $pyExecBase = 'python ./FireBase_Connections/firebaseLookup.py ';
        $pyExecCmd = $pyExecBase . $isbn;
        $fbLookup = exec($pyExecCmd);
        array_push($books, $fbLookup);
	$result['Books'] = $books;
        $result['success'] = true;
        echo json_encode($result);
});

/*
*	Get a Reading List for a User
*	Drizzuto
*	Finished - Drizzuto
*/

$app->get('/getReadingList', function() {
	global $pdo;
        $books = array();
        $pyExecBase = 'python ./FireBase_Connections/firebaseLookup.py ';
	$email = $_GET['email'];

	$statement = $pdo->prepare(
            'SELECT isbn_num, timestamp FROM ReadingList
            WHERE email = :email'
        );
        $statement->bindParam(':email', $email);

	if ($statement->execute()) {
	    while($row = $statement->fetch()) {
                $pyExecCmd = $pyExecBase . $row['isbn_num'];
                $fbLookup = exec($pyExecCmd);
                $bookInfo['book'] = $fbLookup;
                $bookInfo['timestamp'] = $row['timestamp'];
		array_push($books, $bookInfo);
	    }
 
	    $result['Books'] = $books;
	    $result['success'] = true;
	} else {
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


	$statement = $pdo->prepare(
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

/*
*	Remove ratings of a particular user
*	Luke Oglesbee
*	Finished
*/
$app->post('/resetRatingsOfUser', function() {
	global $pdo;

	$args[":email"] = $_POST["email"];

	$statement = $pdo->prepare(
		"DELETE FROM Rating
		WHERE email=:email;"
		);

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
