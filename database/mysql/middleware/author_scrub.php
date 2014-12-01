<?php

Class SafePDO extends PDO {
    public static function exception_handler($exception) {
        die('Uncaught exception: ', $exception->getMessage());
    }

    public function __construct($dsn, $username='', $password='',
        $driver_options=array()) {
    set_exception_handler(array(__CLASS__, 'exception_handler'));
    parent::__construct($dsn, $username, $password, $driver_options);
    restore_exception_handler();
    }
}

$host = '54.69.55.132';
$user = 'test';
$pass = 'Candles';
$dbname = 'BookUpv3';

function pullBookInfo($host, $port, $dbname, $username, $password) {
    $dbh = new SafePDO("mysql:host=$host;port=$port;dbname=$dbname",
        $username, $password);
    $stmt = $dbh->prepare('SELECT * FROM BookList_Good');
    $if ($stmt->execute()) {
        $books = array();
        while ($row = $stmt->fetch(SafePDO::FETCH_ASSOC)) {
            $book['isbn_num'] = $row['isbn_num'];
            $book['title' = $row['title'];
            $book['author'] = $row['author'];
            array_push($books, $book);
        }
    }
    return $books;
}           

function insertBookInfo($host, $port, $dbname, $username, $password) {
    $dbh = new SafePDO("mysql:host=$host;port=$port;dbname=$dbname",
        $username, $password);
    $stmt = $dbh->prepare('');
}

function pullAuthorInfo() {
    global $pdo;
    $statement = $pdo->prepare(
        "SELECT author, isbn_num FROM BookList_Good");
    if ($statement->execute()) {
        $auths = array();
        while ($row = $statement->fetch()) {
            array_push($auths, 
                array(
                    "name" => $row['author'],
                    "isbn_num" => $row['isbn_num'],
                )
            );
        }
    }
    return $auths;
}

# this function has been adapted from an example posted by
# matthewkastor on php.net regarding the function strrchr
# source: http://php.net/manual/en/function.strrchr.php
function splitStringByDelim($char, $str, $side, $keepDelim=true) {
    $offset = ($keepDelim ? 1 : 0);
    $totalLength = strlen($str);
    $rightLength = (strlen(strrchr($str, $char)) - 1);
    $leftLength = ($totalLength - $rightLength - 1);
    switch($side) {
        case 'left':
            $piece = substr($str, 0, ($leftLength + $offset));
            break;
        case 'right':
            $start = (0 - ($rightLength + $offset));
            $piece = substr($str, $start);
            break;
        default:
            $piece = false;
            break;
    }
    return ($piece);
}

function scrubAuthorNames($authorInfo) {
    $scrubbedAuths = array();
    foreach ($authorInfo as $author) {
        $scrubbed = splitStringByDelim(',', $author['name'], 'left', false);
        array_push($scrubbedAuths,
            array(
                "name" => $scrubbed,
                "isbn_num" => $author['isbn_num'],
            )
        );
    }
    return $scrubbedAuths;
}

# get db connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname",
        "$user", "$pass");
} catch (PDOException $e) {
      $respone = "Failed to connect: ";
      $respone .= $e->getMessage();
      die ($response);
}

$authorsInfo = scrubAuthorNames(pullAuthorInfo());
foreach ($authorsInfo as $author) {
    echo $author['name'] . " : " . $author['isbn_num'] . "\n";
}
