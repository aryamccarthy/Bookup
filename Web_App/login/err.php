<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <?php
// show potential errors / feedback (from login object)
    printdiv('You should see this.');

    if (isset($login)) {
        printdiv('Login object is set.');
        if(!empty($_SESSION["email"])) {
            printdiv('Email: ' . $_SESSION["email"]);
        }

        if ($login->errors) {
            prindiv('Login has errors.');
            foreach ($login->errors as $error) {
                printdiv($error);
            }
        }
        if ($login->messages) {
            printdiv('Login has messages.');
            foreach ($login->messages as $message) {
                printdiv($message);
            }
        }
    }
    else { printdiv('Login object is not set.'); }

    function printdiv($msg) {
        echo '<div>Debug: ' . $msg . '</div><br>';
    }

    ?>
</body>
</html>