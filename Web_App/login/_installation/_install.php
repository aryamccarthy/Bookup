<?php

/**
 * This is the installation file for the 0-one-file version of the php-login script.
 * It simply creates a new and empty database.
 */

// error reporting config
error_reporting(E_ALL);

// config
$db_type = "mysql";
$db_mysql_path = "../users.db"; //I'm very unsure of how to use this with our DB...
$db_host = "localhost";
$db_user = "root";
$db_pass = "root";

// create new database file / connection (the file will be automatically created the first time a connection is made up)
$db_connection = new PDO($db_type . ':host=' . $db_host . ';dbname=' . $db_user . ';dbpass=' . $db_pass);

// create new empty table inside the database (if table does not already exist)
$sql = 'CREATE TABLE IF NOT EXISTS `users` (
        `user_password_hash` VARCHAR(255) NOT NULL,
        `user_email` VARCHAR(64) NOT NULL,
        PRIMARY KEY (`user_email`)
        ) ENGINE=InnoDB';

        // -- CREATE UNIQUE INDEX `user_email_UNIQUE` ON `users` (`user_email` ASC);

// execute the above query
$query = $db_connection->prepare($sql);
$query->execute();

// check for success
if (file_exists($db_mysql_path)) {
    echo "Database $db_mysql_path was created, installation was successful.";
} else {
    echo "Database $db_mysql_path was not created, installation was NOT successful. Missing folder write rights ?";
}
