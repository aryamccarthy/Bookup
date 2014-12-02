<?php
Class SafePDO extends PDO {
    public static function exception_handler($exception) {
        die ('Uncaught exception: ' . $exception->getMessage());
    }

    public function __construct($dsn, $username='', $password='',
        $driver_options=array()) {
    set_exception_handler(array(__CLASS__, 'exception_handler'));
    parent::__construct($dsn, $username, $password, $driver_options);
    restore_exception_handler();
    }
}

function pullBookInfo($host, $dbname, $username, $password) {
    try {
        $dbh = new SafePDO("mysql:host=$host;dbname=$dbname",
            $username, $password);
    } catch (PDOException $e) {
          $response = "Failed to connect: ";
          $response .= $e->getMessage();
          die ($response);
    }
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $dbh->prepare('SELECT * FROM BookList_Good');
    if ($stmt->execute()) {
        $books = array();
        while ($row = $stmt->fetch(SafePDO::FETCH_ASSOC)) {
            $book['isbn_num'] = $row['isbn_num'];
            $book['title'] = $row['title'];
            $book['author'] = $row['author'];
            array_push($books, $book);
        }
    }
    $dbh = null;
    return $books;
}           

function insertBookInfo($books, $host, $dbname, $username, $password) {
    try {
        $dbh = new SafePDO("mysql:host=$host;dbname=$dbname",
            $username, $password);
    } catch (PDOException $e) {
          $response = "Failed to connect: ";
          $response .= $e->getMessage();
          die ($response);
    }
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $dbh->prepare(
        'INSERT INTO BookList_Good(isbn_num, title, author) 
        VALUES(:isbn, :title, :author)'
    );
    foreach ($books as $book) {
        $stmt->bindValue(':isbn', $book['isbn_num']);
        $stmt->bindValue(':title', $book['title']);
        $stmt->bindValue(':author', $book['author']);
        if ($stmt->execute()) {
            echo 'successfully inserted values' . PHP_EOL;
        } else {
              $errorInfo = $dbh->errorInfo();
              echo 'statement did not execute: ' . $errorInfo[2] . PHP_EOL;
        }      
    }
    $dbh = null;
}

function pullAuthorInfo($host, $dbname, $username, $password) {
    try {
        $dbh = new SafePDO("mysql:host=$host;dbname=$dbname",
            $username, $password);
    } catch (PDOException $e) {
          $response = "Failed to connect: ";
          $response .= $e->getMessage();
          die ($response);
    }
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $dbh->prepare(
        "SELECT author, isbn_num FROM BookList_Good");
    if ($stmt->execute()) {
        $auths = array();
        while ($row = $stmt->fetch()) {
            array_push($auths, 
                array(
                    "name" => $row['author'],
                    "isbn_num" => $row['isbn_num'],
                )
            );
        }
    }
    $dbh = null;
    return $auths;
}

function updateAuthorInfo($authorInfo, $host, $dbname,
    $username, $password) {
    try {
        $dbh = new SafePDO("mysql:host=$host;dbname=$dbname",
            $username, $password);
    } catch (PDOException $e) {
          $response = "Failed to connect: ";
          $response .= $e->getMessage();
          die ($response);
    }
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $dbh->prepare(
        "UPDATE BookList_Good SET author=:auth WHERE isbn_num=:isbn"
    );
    foreach ($authorInfo as $author) {
        $stmt->bindValue(':auth', $author['name']);
        $stmt->bindValue(':isbn', $author['isbn_num']);
        if ($stmt->execute()) {
            echo 'successfully updated values' . PHP_EOL;
        } else {
              $errorInfo = $dbh->errorInfo();
              echo 'statement did not execute: ' . $errorInfo[2] . PHP_EOL;
        }
    }
    $dbh = null;
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

$authorInfo = scrubAuthorNames(pullAuthorInfo('54.69.55.132',
    'BookUpv4', 'test', 'Candles'));
updateAuthorInfo($authorInfo, '54.69.55.132',
    'BookUpv4', 'test', 'Candles');
?>
