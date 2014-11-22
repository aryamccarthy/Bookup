<?php

// Datbase information
// Put your stuff here

require 'vendor/autoload.php';

$host = '54.69.55.132';
$user = 'test';
$pass = 'Candles';

// Get DB connection
$app = new \Slim\Slim();
try {
    $pdo = new PDO("mysql:host=$host;dbname=BookUpv3", "$user", "$pass");
    # echo "connected to db";
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

	$statement = $pdo->prepare(
            "SELECT * FROM
            BookList_Good b INNER JOIN PopularBookList p
            ON b.isbn_num = p.isbn_num
            WHERE language = :lang"
        );
        $lang = 'en';
        $statement->bindParam(':lang',$lang);

	if ($statement->execute()) {
            $books = array();
            while($row = $statement->fetch()) {
                $book['title'] = $row['title'];
                $book['author'] = rtrim($row['author'], ", ");
                $book['description'] = $row['description'];
                $book['isbn_num'] = $row['isbn_num'];
                $book['thumbnail'] = $row['image_link'];
                array_push($books, $book);
	    }

            $result['books'] = $books;
            $result['success'] = true;
        } else {
              $result['books'] = array();
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
			WHERE email = :email");

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
*	Checks to see the user/pass_hash combination is correct
*	
*	owner: Nicole Gatmaitan
*	status: Working
*
*	Last tested on 11/12/2014 at 9:08pm
*/

$app->get('/validate/:email/:pass_hash', function($email, $password) {
	global $pdo;

	$args [":email"] = $email;
	$args [":pass_hash"] = $password;

	$statement = $pdo->prepare(
		"SELECT COUNT(email) AS count FROM Account
			WHERE email = :email AND pass_hash = :pass_hash");

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
			WHERE email = :email");

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
	$args [":pass_hash"] = $_POST['pass_hash'];

	$statement = $pdo->prepare(
		"INSERT INTO Account (email, pass_hash)
		VALUES (:email, :pass_hash)");

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

	$statement = $pdo->prepare(
		'SELECT * FROM BookList_Good
		ORDER BY RAND() LIMIT 1');

	if ($statement->execute()) {
		$books = array();
                
		while($row = $statement->fetch($fetch_style=$pdo::FETCH_ASSOC))
		{
                        $book['title'] = $row['title'];
                        $book['author'] = rtrim($row['author'], ", ");
                        $book['description'] = $row['description'];
                        $book['isbn_num'] = $row['isbn_num'];
                        $book['thumbnail'] = $row['image_link'];
                        array_push($books, $book);	
		}
                $result['books'] = $books; 
		$result['success'] = true;
	}
	else {
                $result['books'] = array();
		$result["success"] = false;
		$result["error"] = $statement->errorInfo();
	}

	echo json_encode($result);

}

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
    
	$args[':email'] = $email;

	$statement = $pdo->prepare(
            'SELECT * FROM
            BookList_Good b INNER JOIN ReadingList r
            ON b.isbn_num = r.isbn_num
            WHERE email = :email
            ORDER BY timestamp DESC'
        );

	if ($statement->execute($args)) {
		$books = array();

		while($row = $statement->fetch($fetch_style=$pdo::FETCH_ASSOC))
		{
                        $book['title'] = $row['title'];
                        $book['author'] = rtrim($row['author'], ", ");
                        $book['description'] = $row['description'];
                        $book['isbn_num'] = $row['isbn_num'];
                        $book['thumbnail'] = $row['image_link']; 
			array_push($books, $book);
		} 
		$result['books'] = $books;
		$result['success'] = true;
	}
	else {
                $result['books'] = array();
		$result['success'] = false;
		$result['error'] = $statement->errorInfo();
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
	    (:email, NOW(), :isbn)'
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
		UPDATE rating=VALUES(rating)");
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

$app->get('/searchTest', function() {
    global $pdo;

    $statement = $pdo->prepare(
        "SELECT * FROM BookList_Good
        ORDER BY RAND() LIMIT 2"
    );

    if ($statement->execute()) {
        $books = array();
        while($row = $statement->fetch()) {
            $book['title'] = $row['title'];
            $book['author'] = rtrim($row['author'], ", ");
            $book['description'] = $row['description'];
            $book['isbn'] = $row['isbn_num'];
            $book['thumbnail'] = $row['image_link'];
            array_push($books, $book);
        }
        $result['books'] = $books;
        $result['success'] = true;
    } else {
          $result['books'] = array();
          $result['success'] = false;
          $errorData = $statement->errorInfo();
          $result['error'] = $errorData[2];
   }
   return json_encode($result);
});

$app->get('/searchForBook', function() {
    global $pdo;
    $books = array();

    $statement = $pdo->prepare(
        "SELECT * FROM BookList_Good
        ORDER BY RAND() LIMIT 10"
    );

    if ($statement->execute()) {
        while($row = $statement->fetch($fetch_style=$pdo::FETCH_ASSOC)) {
            $book['title'] = $row['title'];
            $book['author'] = rtrim($row['author'], ", ");
            $book['description'] = $row['description'];
            $book['isbn_num'] = $row['isbn_num'];
            $book['thumbnail'] = $row['image_link'];
            array_push($books, $book);
        }
        $result['books'] = $books;
        $result['success'] = true;
    } else {
          $result['books'] = array();
          $books['success'] = false;
          $errorData = $statement->errorInfo();
          $books['error'] = $errorData[2];
   }
   return json_encode($books);
});
        
//	^^^^^^^^^^^^^^^^^^^^^^^
//	FUNCTIONS GO ABOVE HERE
//


$app->run();
?>
