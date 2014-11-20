<?php

// Datbase information
// Put your stuff here

include './FireBase_Connections/firebaseIsbnLookup.php';
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
*   Test
*/
$app->get('/test', function() {
	echo "success";
});

/*
*	Remove ratings of a particular user
*	
*	owner: Danny Rizzuto
*	status: Working
*
*	Last tested by Luke on 11/2/2014 at 1:50pm
*/

$app->get('/getPopularBooks', function() {
	global $pdo;

        $firebaseObject = new FirebaseIsbnLookup();

	$statement = $pdo->prepare("SELECT * FROM PopularBookList");

	if ($statement->execute()) {
            $books = array();
            while($row = $statement->fetch()) {
                $bookObject = $firebaseObject->getBookJson($row['isbn_num']);
                $bookObject = firebaseJsonToSpecJson($bookObject);
                array_push($books, $bookObject);
	    }

            $result['Books'] = $books;
            $result['success'] = true;
        } else {
              $result['success'] = false;
	      $result['error'] =$statement->errorInfo();
	}
     
    echo json_encode($result);
});

/*
*	Checks to see if the user exists
*	
*	owner: Nicole Gatmaitan
*	status: Working
*
*	Last tested by Nicole on 11/2/2014 at 2:24pm
*/

$app->get('/userExists/:email', function($email) {
	global $pdo;

	$args [":email"] = $email;

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

/*
*	Checks to see the user/password combination is correct
*	
*	owner: Nicole Gatmaitan
*	status: Working
*
*	Last tested on 11/12/2014 at 9:08pm
*/

$app->get('/validate/:email/:password', function($email, $password) {
	global $pdo;

	$args [":email"] = $email;
	$args [":password"] = $password;

	$statement = $pdo->prepare(
		"SELECT COUNT(email) AS count FROM Account
			WHERE email = :email AND password = :password");

	if ($statement->execute($args)) {
		$result["success"] = true;
		$row = $statement->fetch($fetch_style=$pdo::FETCH_ASSOC);
		$result['valid'] = $row['count'] != 0;
		$result['error'] = $result['valid'] ? '' : 'The combination is incorrect.';
	}
	else {
		$result["success"] = false;
		$result["error"] = $statement->errorInfo();
	}

	echo json_encode($result);

});


/*
*	Checks to see the user is new
*	
*	owner: Nicole Gatmaitan
*	status: Working
*
*	Last tested by Nicole on 11/2/2014 at 2:26pm
*/

$app->get('/isNewUser/:email', function($email) {
	global $pdo;

	$args [":email"] = $email;

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

/*
*	Adds user to database
*	
*	owner: Nicole Gatmaitan
*	status: Working
*
*	Last tested by Nicole on 11/2/2014 at 2:27pm
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
*	Get a recommended Book from FB
*
*	owner: Luke Oglesbee
*	status: Hollow (calls getrandombook)
*
*	Last tested by Luke on 11/12/2014
*/

$app->get('/getRecommendedBook/:email', function($email) {
	getRandomBook();
});

/*
*	Get a Random Book from FB
*	
*	owner: Danny Rizzuto
*	status: Working
*
*	Last tested by Danny on 11/2/2014 at 2:29pm
*/

$app->get('/getRandomBook',function() {
	getRandomBook();
}); 

function getRandomBook() {
	global $pdo;

    $firebaseObject = new FirebaseIsbnLookup();

	$statement = $pdo->prepare(
		'SELECT isbn_num FROM BookList
		ORDER BY RAND() LIMIT 1;');

	if ($statement->execute()) {
		$books = array();
                
		while($row = $statement->fetch($fetch_style=$pdo::FETCH_ASSOC))
		{
			try {
				/* 
				* Old version, returning several book objects
				*/
				$bookObject = $firebaseObject->getBookJson($row['isbn_num']);
				$bookObject = firebaseJsonToSpecJson($bookObject);
				array_push($books, $bookObject);
				$result['Books'] = $books;

				/* 
				* New version, returns the books json in the spec doc
				*/
				$fbook = $firebaseObject->getBookJson($row['isbn_num']);
				$fbook = 

				$result['success'] = true;
			}
			catch (Exception $e) {
				$result["success"] = false;
				$result["error"] = "The ISBN is not in firebase";
			}
		} 
	}
	else {
		$result["success"] = false;
		$result["error"] = $statement->errorInfo();
	}

	echo json_encode($result);

}

/*
*	Get Book Object From Firebase
*	
*	owner: Danny Rizzuto
*	status: Working
*
*	Last tested by Luke on 11/2/2014 at 1:50pm
*/

$app->get('/getBookFromFirebase/:isbn', function($isbn) {
	global $pdo;
    $args[':isbn'] = $isbn;
    $firebaseObject = new FirebaseIsbnLookup();
    $bookObject = $firebaseObject->getBookJson($args[':isbn']);
    $prettyBook = firebaseJsonToSpecJson($bookObject);
    echo json_encode($prettyBook);
});

/*
*	Get a user's reading list
*	
*	owner: Danny Rizzuto
*	status: Working
*
*	Last tested by Danny on 11/2/2014 at 2:28pm
*/

$app->get('/getReadingList/:email', function($email) {
	global $pdo;
    
    $firebaseObject = new FirebaseIsbnLookup();

	$args[':email'] = $email;

	$statement = $pdo->prepare(
            'SELECT isbn_num, timestamp FROM ReadingList
            WHERE email = :email'
        );

	if ($statement->execute($args)) {
		$books = array();

		while($row = $statement->fetch($fetch_style=$pdo::FETCH_ASSOC))
		{
			//echo $row["isbn_num"];
			$bookObject = $firebaseObject->getBookJson($row["isbn_num"]);
			$bookObject = firebaseJsonToSpecJson($bookObject);
			array_push($books, $bookObject);
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
*	Add book to reading list
*	
*	owner: Zack Fout
*	status: Working
*
*	Last tested by Luke on 11/2/2014 at 1:50pm
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
*	Add a book and user rating to Rating
*	
*	owner: Luke Oglesbee
*	status: Working
*
*	Last tested by Luke on 11/2/2014 at 2:38pm
*/

$app->post('/submitBookFeedback', function() {
	global $pdo;

	$args[':email'] = $_POST['email'];
	$args[':rating'] = $_POST['rating'];
	$args[':isbn'] = $_POST['isbn'];

	$statement = $pdo->prepare(
		"INSERT INTO Rating(email, rating, timestamp, isbn_num) VALUES 
		(:email, :rating, NOW(), :isbn)
		ON DUPLICATE KEY
		UPDATE rating=VALUES(rating);");
	if ($statement->execute($args)) {
		$result["success"] = true;
	} else {
		$result["success"] = false;
		$result["error"] = $statement->errorInfo();
	}
	
	echo json_encode($result);

});

/*
*	Remove Book from Reading List
*
*	owner: Luke Oglesbee
*	status: Working
*	
*	Last tested by Luke on 11/2/2014 at 1:50pm
*/
$app->post('/removeBookFromReadingList', function() {
	global $pdo;

	$args[":email"] = $_POST['email'];
	$args[":isbn"] = $_POST['isbn'];
	
	$statement = $pdo->prepare(
		"DELETE FROM ReadingList
		WHERE email=:email AND isbn_num=:isbn;");

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
*	
*	owner: Luke Oglesbee
*	status: Working
*
*	Last tested by Luke on 11/2/2014 at 1:50pm
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

$app->get('/fixingJson/:isbn', function($isbn) {
	global $pdo;
    $args[':isbn'] = $isbn;
    $firebaseObject = new FirebaseIsbnLookup();
    $bookObject = $firebaseObject->getBookJson($args[':isbn']);
    $prettyBook = firebaseJsonToSpecJson($bookObject);
    echo json_encode($prettyBook);
});

function firebaseJsonToSpecJson($fire, $isbn=null) {
	$fire = json_decode($fire, $assoc=true);
	if ($fire["totalItems"] < 1) {
		return null;
	}
	$fire = $fire["items"][0];
	$prettyBook["title"] = (empty($fire["volumeInfo"]["title"]) ? null : $fire["volumeInfo"]["title"]);
	$prettyBook["author"] = (empty($fire["volumeInfo"]["authors"]) ? null : $fire["volumeInfo"]["authors"]);
	$prettyBook["description"] = (empty($fire["volumeInfo"]["description"]) ? null : $fire["volumeInfo"]["description"]);
	$prettyBook["isbn"] = null;
	if ($isbn) {
		$prettyBook["isbn"] = $isbn;
	} else {
		foreach ($fire["volumeInfo"]["industryIdentifiers"] as $isbn) {
			if ($isbn["type"] == "ISBN_13") {
				$prettyBook["isbn"] = (empty($isbn["identifier"]) ? null : $isbn["identifier"]);
				break;
			}
		}
	}
	$prettyBook["thumbnail"] = (empty($fire["volumeInfo"]["imageLinks"]["thumbnail"])) ? null : $fire["volumeInfo"]["imageLinks"]["thumbnail"];
	return $prettyBook;
}

//	^^^^^^^^^^^^^^^^^^^^^^^
//	FUNCTIONS GO ABOVE HERE
//


$app->run();
?>
