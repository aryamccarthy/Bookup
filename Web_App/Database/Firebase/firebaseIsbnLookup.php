<?php

/* 
 * Code Started on 10/05/14 by Daniel Rizzuto
 * Code Finished on 10/21/14 by Daniel Rizzuto
 * Worked on by: {}
 * Program loads json from goole books api and sends to Firebase
 */

include 'firebase.php';

class FirebaseIsbnLookup {

    public function __construct() {

    }

    public function getBookJson($isbn) {

        $fbURL = 'https://blistering-torch-3821.firebaseio.com/';

        echo "$fbURL$isbn\n";

        $fbConnection = new fireBase("$fbURL$isbn");

        $result = $fbConnection->val();

        echo json_encode($result);

    }

}

$testObj = new FirebaseIsbnLookup();

$isbn = '9780547249643';

$testObj->getBookJson($isbn);

?>
