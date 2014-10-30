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
        if ($login->errors) {
            prindiv('Login has errors.');
            foreach ($login->errors as $error) {
                // echo '<p>' . $error . '</p>' . '<br>';
            }
        }
        if ($login->messages) {
            printdiv('Login has messages.');
            foreach ($login->messages as $message) {
                // echo '<p>' . $message . '</p>' . '<br>';
            }
        }
    }

    function printdiv($msg) {
        echo '<p>' . $msg . '</p>' . '<br>';
    }

    ?>
</body>
</html>