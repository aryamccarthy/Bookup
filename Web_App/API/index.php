<?php

// Datbase information
// Put your stuff here

include './FireBase_Connections/firebaseIsbnLookup.php';
require 'vendor/autoload.php';

$host = '54.69.55.132';
$user = 'test';
$pass = 'Candles';
$dbname = 'RecommenderDev';

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
//	Don't forget to import the global pdo
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
                $book['author'] = $row['author'];
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
	$args [":password"] = $_POST['password'];

	$statement = $pdo->prepare(
		"INSERT INTO Account (email, password)
		VALUES (:email, :password)");

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
* 	status: development
*
*	Not tested
*/

$app->get('/getRecommendedBook/:email', function($email) {
	global $pdo;
	$args[':email'] = $email;

	# Get similar users
	$statement = $pdo->prepare(
		"CREATE TEMPORARY TABLE rank AS
			SELECT similar.email, count(*) rank
			FROM Rating target
			JOIN Rating similar ON target.isbn_num=similar.isbn_num AND target.email != similar.email
			WHERE target.email = :email AND target.rating = similar.rating
			GROUP BY similar.email;");

	if (!$statement->execute($args)) {
		$result['success'] = False;
		$result['error'] = $statement->errorInfo();
		echo json_encode($result);
		return;
	}

	# Get recommonded books as isbn_nums
	# Do not fetch books that the user has seen in the past 24 hours
	$statement = $pdo->prepare(
		"SELECT similar.isbn_num, sum(rank.rank) total_rank
		FROM rank
		JOIN Rating similar on rank.email = similar.email
		LEFT JOIN Rating target ON target.email=:email and target.isbn_num = similar.isbn_num
		LEFT JOIN (
			SELECT isbn_num
			FROM BookSeen
			WHERE email = :email and timestamp >= NOW() - INTERVAL 1 DAY
		) as seen
		ON similar.isbn_num = seen.isbn_num
		WHERE target.isbn_num is NULL and seen.isbn_num is NULL
		GROUP BY similar.isbn_num
		ORDER BY total_rank DESC;");
	if (!$statement->execute($args)) {
		$result['success'] = False;
		$result['error'] = $statement->errorInfo();
		echo json_encode($result);
		return;
	}

	# Get the highest recommended book
	# If there are none, return a random book
	$result['books'] = [];
	$isbn = null;
	if ($recIsbnRow = $statement->fetch($fetch_style=$pdo::FETCH_ASSOC)) {
		# Get full book info
		$isbn = $recIsbnRow['isbn_num'];
		$result['random'] = False;
	} else {
		# Get a random book that the user hasn't seen yet
		$result['random'] = True;
		$statement = $pdo->prepare(
			"SELECT BookList_Good.isbn_num FROM BookList_Good
			LEFT JOIN (
				SELECT isbn_num
				FROM BookSeen
				WHERE email = :email
			) as seen
			ON BookList_Good.isbn_num = seen.isbn_num
			WHERE seen.isbn_num is NULL
			ORDER BY RAND();");
		if (!$statement->execute($args)) {
			$result['success'] = False;
			$result['debug'] = 1;
			echo json_encode($result);
			return;
		}
		if ($randIsbnRow = $statement->fetch($fetch_style=$pdo::FETCH_ASSOC)) {
			$isbn = $randIsbnRow['isbn_num'];
		} else {
			$result['success'] = False;
			echo json_encode($result);
			return;
		}
	}

	# Get the rest of the book info
	$statement = $pdo->prepare(
		"SELECT * FROM BookList_Good
		WHERE isbn_num = $isbn;");
	if (!$statement->execute()) {
		$result['success'] = False;
		$result['debug'] = 4;
		echo json_encode($result);
		return;
	}
	if ($bookInfo = $statement->fetch($fetch_style=$pdo::FETCH_ASSOC)) {
		array_push($result['books'], rowToSpecJson($bookInfo));
	} else {
		$result['success'] = False;	
		echo json_encode($result);
		return;
	}

	# Mark the book in BookSeen
	$args[':isbn'] = $result['books'][0]['isbn'];
	$statement = $pdo->prepare(
		"INSERT INTO BookSeen(email, timestamp, isbn_num)
		VALUES(:email, NOW(), :isbn);");
	if ($statement->execute($args)) {
		$result['success'] = True;
	} else {
		$result['success'] = False;
        $result['error'] = $statement->errorInfo();
        $result['debug'] = 3;
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

$app->get('/getRecommendedBook2/:email', function($email) {
	global $pdo;
	$args[':email'] = $email;

	$statement = $pdo->prepare(
		"SELECT BL.isbn_num
        FROM BookList_Good BL
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
        ORDER BY RAND()
        LIMIt 20;");

	if ($statement->execute($args)) {
		$max_isbn = null;
		$max_guess = -1;
        $book = null;
		while ($row = $statement->fetch()) {
			$isbn = $row['isbn_num'];
            // $book = rowToSpecJson($row);
			$recCheck = recCheck($email, $isbn);
			$guess = $recCheck['guess']+0.0;
			if ($guess > $max_guess) {
				$max_isbn = $isbn;
				$max_guess = $guess;
			}
		}
		if ($book == null) {
			// User has seend every book in the DB in the past 24 hours.
			// TODO: reset BookSeen for that user
		}
		$result['success'] = True;
        $result['isbn'] = $max_isbn;
		// $result['books'] = array($book);
		$result['guess'] = $max_guess;
	} else {
		$result['success'] = False;
        $result["error"] = $statement->errorInfo();
        echo json_encode($result);
        return;
	}

	$args[':isbn'] = $max_isbn;
	$statement = $pdo->prepare(
		"INSERT INTO BookSeen(email, timestamp, isbn_num)
		VALUES(:email, NOW(), :isbn)  ;");
	if ($statement->execute($args)) {
		$result['success'] = True;
	} else {
		$result['success'] = False;
        $result['error'] = $statement->errorInfo();
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
*	Get a Random Book
*	
*	owner: Danny Rizzuto
*	status: Working
*
*	Last tested by Danny on 11/2/2014 at 2:29pm
*/

$app->get('/getRandomBook',function() {
	global $pdo;
	$result = getRandomBook($pdo);
	echo json_encode($result);
}); 

function getRandomBook($pdo) {
	$statement = $pdo->prepare(
		'SELECT * FROM BookList_Good
		ORDER BY RAND();');

	if ($statement->execute()) {
		$books = array();
                
		while($row = $statement->fetch($fetch_style=$pdo::FETCH_ASSOC))
		{
            array_push($books, rowToSpecJson($row));	
		}
        $result['books'] = $books; 
		$result['success'] = true;
	}
	else {
        $result['books'] = array();
		$result["success"] = false;
		$result["error"] = $statement->errorInfo();
	}

	return $result;
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
			array_push($books, rowToSpecJson($row));
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
*	Reccomendation Check (Test function)
*
*	Luke Oglesbee
*	Development (not done)
*/

$app->get('/recCheck/:email/:isbn', function($email, $isbn) {
	$result = recCheck($email, $isbn);
	print json_encode($result);
});

/*
*	Submit Book Feedback
*
*	Luke Oglesbee
*	Develpment (testing)
*
*	Last tested by Luke on 11/19/14
*/

$app->post('/submitBookFeedback', function() {
	global $pdo;
	$args[':email'] = $_POST['email'];
	$args[':rating'] = $_POST['email'];
	$args[':isbn'] = $_POST['isbn'];

	$statement = $pdo->prepare(
		"INSERT INTO Rating(email,rating,timestamp,isbn_num) VALUES
		(:email, :rating, NOW(), :isbn)
		ON DUPLICATE KEY
		UPDATE rating = rating;");
	if ($statement->execute($args)) {
		$result['success'] = True;
	} else {
		$result['success'] = False;
		$result['error'] = $statement->errorInfo();
	}

	echo json_encode($result);
}); 

/*
*	Submit Book Feedback
*
*	Luke Oglesbee
*	Develpment (testing)
*
*	Last tested by Luke on 11/19/14
*/

$app->post('/submitBookFeedback2', function() {
	$email = $_POST['email'];
    $rating = $_POST['rating'];
    $isbn = $_POST['isbn'];
    submitBookFeedback($email, $rating, $isbn);
});

function submitBookFeedback($email, $rating, $isbn) {
    global $pdo;

    $args[':email'] = $email;
    $args[':rating'] = $rating;
    $args[':isbn'] = $isbn;

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

    # Update Compare table...
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
}

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
   
       
/**************************************************
*   Helper functions
***************************************************/

function rowToSpecJson($row) {
    /*
    *   Convert a row of a Booklist to spec array
    *
    *   creator: Luke Oglesbee
    *   status: working
    */
    $book = array();
    $book['title'] = $row['title'];
    $book['author'] = $row['author'];
    $book['description'] = $row['description'];
    $book['isbn'] = $row['isbn_num'];
    $book['thumbnail'] = $row['image_link'];
    return $book;
}

function recCheck($email, $isbn) {
	/*
    *   Gets the recommenders guess for how a user would rate a book
    *
    *   owner: Luke Oglesbee
    *   status: Development
    *
    *   Not tested
    */
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

//	^^^^^^^^^^^^^^^^^^^^^^^
//	FUNCTIONS GO ABOVE HERE
//

$app->run();
?>
