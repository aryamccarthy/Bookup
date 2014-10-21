<?php

/* 
 * Code Started on 10/05/14 by Daniel Rizzuto
 * Code Finished on 10/21/14 by Daniel Rizzuto
 * Worked on by: {}
 * Program receives an isbn number and returns the large Book Json object
 * Must pass isbn as a string, not a number
 */

include 'firebase.php';

class FirebaseIsbnLookup {

    public function __construct() {

    }

    public function getBookJson($isbn) {

        $fbURL = 'https://blistering-torch-3821.firebaseio.com/';

        $fbConnection = new fireBase("$fbURL$isbn");

        $result = $fbConnection->val();

        echo json_encode($result);

    }

}

//Example way to retreive gooogle books info on 1984 by George Orwell

$testObj = new FirebaseIsbnLookup();

$isbn = '9780547249643';

$testObj->getBookJson($isbn);



?>