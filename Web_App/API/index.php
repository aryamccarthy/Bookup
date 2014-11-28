<?php

// Datbase information
// Put your stuff here

include './FireBase_Connections/firebaseIsbnLookup.php';
include 'recommender.php';
require 'vendor/autoload.php';

$host = 'localhost';
$user = 'root';
$pass = 'root';
$dbname = 'Recommender';

// Get DB connection
$app = new \Slim\Slim();
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", "$user", "$pass");
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

/*
*	Checks to see the user is new
*	
*	owner: Nicole Gatmaitan
*	status: Working
*
*	Last tested by Nicole on 11/2/2014 at 2:26pm
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
*	Get a recommended Book
*
*	owner: Luke Oglesbee
*	status: Development
*
*	Not tested
*/

$app->get('/getRecommendedBook/:email', function($email) {
	global $pdo;
	$args[':email'] = $email;

	$statement = $pdo->prepare(
		"SELECT BL.isbn_num
		FROM BookList BL
		LEFT JOIN (
			SELECT isbn_num
			FROM Rating
			WHERE email=:email
		) AS R
		ON BL.isbn_num = R.isbn_num
		LEFT JOIN (
			SELECT isbn_num
			FROM BookSeen
			WHERE email=:email AND timestamp >= NOW() - INTERVAL 1 DAY
		) as B
		ON BL.isbn_num = B.isbn_num
		WHERE R.isbn_num is NULL AND B.isbn_num is NULL
		ORDER BY rand();");

	if ($statement->execute($args)) {
		$max_isbn = null;
		$max_guess = -1;
		while ($row = $statement->fetch()) {
			$isbn = $row['isbn_num'];
			$recCheck = recCheck($email, $isbn);
			$guess = $recCheck['guess']+0.0;
			if ($guess > $max_guess) {
				$max_isbn = $isbn;
				$max_guess = $guess;
			}
		}
		if ($max_isbn == NULL) {
			// User has seend every book in the DB in the past 24 hours.
			// TODO: reset BookSeen for that user
		}
		$result['success'] = True;
		$result['isbn'] = $max_isbn;
		$result['guess'] = $max_guess;
	} else {
		$result['success'] = False;
	}

	$args[':isbn'] = $max_isbn;
	$statement = $pdo->prepare(
		"INSERT INTO BookSeen(email, timestamp, isbn_num)
		VALUES(:email, NOW(), :isbn);");
	if ($statement->execute($args)) {
		$result['success'] = True;
	} else {
		$result['success'] = False;
	}
	echo json_encode($result);
});

/*
*	Reset BookSeen for a user
*
*	owner: Luke Oglesbee
*	status: Development
*
*	Not tested by no one never.
*/

$app->get('/resetBookSeen/:email', function($email) {
	global $pdo;
	$args[':email'] = $email;
	$statement = $pdo->prepare(
		"DELETE FROM BookSeen
		WHERE email=:email;");
	if ($statement->execute($args)) {
		$result['success'] = True;
	} else {
		$result['success'] = False;
	}
	echo json_encode($result);
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
*	Reccomendation Check
*
*	Luke Oglesbee
*	Development (not done)
*/

$app->get('/recCheck/:email/:isbn', function($email, $isbn) {
	$result = recCheck($email, $isbn);
	print json_encode($result);
});

function recCheck($email, $isbn) {
	global $pdo;

	$args[":email"] = $email;
	$args[":isbn"] = $isbn;
	$denom = 0.0;
	$numer = 0.0;
	$k = $isbn;

	$statement = $pdo->prepare(
		"SELECT r.isbn_num, r.rating
		FROM Rating r
		WHERE r.email=:email AND r.isbn_num<>:isbn;");
	if ($statement->execute($args)) {
		$result["success"] = true;
	} else {
		$result["success"] = false;
		$result["error"] = $statement->errorInfo();
		echo json_encode($result);
		return;
	}

	while($row = $statement->fetch()) {
		$j = $row["isbn_num"];
		$rating = $row["rating"];
		# Get the number of times k and j have both been rated by the same user
		$statement2 = $pdo->prepare(
			"SELECT c.count, c.sum
			FROM Compare c
			WHERE isbn_num1=$k AND isbn_num2=$j;");
		if ($statement2->execute($args)) {
			$result["success"] = true;
		} else {
			$result["success"] = false;
			$result["error"] = $statement2->errorInfo();
			echo json_encode($result);
			return;
		}
		while ($res = $statement2->fetch()) {
			$count = $res["count"];
			$sum = $res["sum"];
			$average = $sum / $count;
			$denom += $count;
			$numer += $count * ($average + $rating);
		}
	}
	if ($denom == 0) {
		$result["guess"] = NULL;
	} else {
		$result["guess"] = ($numer/$denom);
	}

	return $result;
}

/*
*	Rate (SubmitBookFeedback)
*
*	Luke Oglesbee
*	Develpment (testing)
*
*	Last tested by Luke on 11/19/14
*/

$app->post('/rate', function() {
	global $pdo;

	$args[':email'] = $_POST['email'];
	$args[':rating'] = $_POST['rating'];
	$args[':isbn'] = $_POST['isbn'];

	# Insert New Rating
	$statement = $pdo->prepare(
		"INSERT INTO Rating(email, rating, timestamp, isbn_num) VALUES 
		(:email, :rating, NOW(), :isbn);");
	if ($statement->execute($args)) {
		$result["success"] = true;
	} else {
		$result["success"] = false;
		$result["error"] = $statement->errorInfo();
		echo json_encode($result);
		return;
	}

	# Update dev table...
	$statement = $pdo->prepare(
		"SELECT DISTINCT r.isbn_num, r2.rating - r.rating as ratingDifference
		FROM Rating r, Rating r2
		WHERE r.email = :email AND r2.isbn_num = :isbn AND r2.email = :email;");
	if ($statement->execute($args)) {
		$result["success"] = true;
	} else {
		$result["success"] = false;
		$result["error"] = $statement->errorInfo();
	}
	unset($args[":email"]);
	unset($args[":rating"]);
	while($row = $statement->fetch()) {
		$args[":otherIsbn"] = $row["isbn_num"];
		$args[":ratingDifference"] = $row["ratingDifference"];
		
		if ($args[":isbn"] == $args[":otherIsbn"]) {
			continue;
		}
		$statement2 = $pdo->prepare(
			"INSERT INTO Compare VALUES (:isbn, :otherIsbn, 1, :ratingDifference)
			ON DUPLICATE KEY UPDATE count=count+1, sum=sum+:ratingDifference;");
		if ($statement2->execute($args)) {
			$result["success"] = true;
		} else {
			$result["success"] = false;
			$result["error"] = $statement2->errorInfo();
			echo json_encode($result);
			return;
		}
		$statement2 = $pdo->prepare(
			"INSERT INTO Compare VALUES (:otherIsbn, :isbn, 1, :ratingDifference)
			ON DUPLICATE KEY UPDATE count=count+1, sum=sum+:ratingDifference;");
		if ($statement2->execute($args)) {
			$result["success"] = true;
		} else {
			$result["success"] = false;
			$result["error"] = $statement2->errorInfo();
			echo json_encode($result);
			return;
		}
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

/*
*   Helper functions
*/

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
