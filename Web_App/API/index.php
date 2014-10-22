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


});

/**
*	Submit Setup Book Preferences
*
*	Owner: Nicole
*/
$app->post('/submitSetupBookPrefs', function() {


});








//	^^^^^^^^^^^^^^^^^^^^^^^
//	FUNCTIONS GO ABOVE HERE
//


$app->run();


?>
