<?php
include 'firebase.php'

class FirebaseIsbnLookup {
    
    public function __construct() {
    
    }

    public function getBookJson($isbn) {
    
        $fbURL = 'https://bookup-v2.firebaseio.com/';
    
        $isbn = (string)$isbn;

        $fbConnection = new firebase("$fbURL$isbn");

        $result = $fbConnection->val();

        reset($result);

        $firstKey = key($result);

        return $result[$firstKey];
    }
}
