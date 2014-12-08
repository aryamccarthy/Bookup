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

function pullTitleInfo($host, $dbname, $username, $password) {
    try {
        $dbh = new SafePDO("mysql:host=$host;dbname=$dbname",
            $username, $password);
    } catch (PDOException $e) {
          $response = "Failed to connect: ";
          $response .= $e->getMessage();
          die($response);
    }
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $dbh->prepare('
        SELECT title, isbn_num FROM BookList_Good
        WHERE length(title) = :len
        ORDER BY title ASC'
    );
    $len = 30;
    $stmt->bindParam(':len', $len);
    if ($stmt->execute()) {
        $titles = array();
        while ($row = $stmt->fetch(SafePDO::FETCH_ASSOC)) {
            array_push($titles, array(
                "title" => $row['title'],
                "isbn" => $row['isbn_num'],
            ));
        }
    }
    $dbh = null;
    return $titles;
}

$titlesInfo = pullTitleInfo('54.69.55.132',
    'BookUpv4', 'test', 'Candles');
foreach ($titlesInfo as $title) {
    echo $title['title'] . ": " . $title['isbn'] . PHP_EOL;
}

?>
