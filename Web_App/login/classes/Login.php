<?php

function debug($msg) {
    // echo '<div>Debug: ' . $msg . '</div><br>';
}

// function timeout() {
//     const $inactive = 7200; //2-hour timeout

//     if(!isset($_SESSION['countdown'])) {
//         $_SESSION['countdown'] = $inactive;
//     }
//     if(!isset($_SESSION['now'])) {
//         $_SESSION['now'] = time();
//     }
//     if(!isset($_SESSION['delta'])) {
//         $_SESSION['delta'] = 0;
//     }

//     $delta = time() - $_SESSION['now'];
//     $_SESSION['countdown'] -= $delta;
//     $_SESSION['now'] = time();
// }

/**
 * Class login
 * handles the user's login and logout process
 */
class Login
{
    /**
     * @var object The database connection
     */
    private $db_connection = null;
    /**
     * @var array Collection of error messages
     */
    public $errors = array();
    /**
     * @var array Collection of success / neutral messages
     */
    public $messages = array();

    /**
     * the function "__construct()" automatically starts whenever an object of this class is created,
     * you know, when you do "$login = new Login();"
     */
    public function __construct()
    {
        debug("Login constructor.");
        // create/read session, absolutely necessary
        session_start();

        // check the possible login actions:
        // if user tried to log out (happen when user clicks logout button)
        if (isset($_GET["logout"])) {
            debug("logout");
            $this->doLogout();
        }
        // login via post data (if user just submitted a login form)
        elseif (isset($_POST["login"])) {
            debug("login");
            $this->dologinWithPostData();
        }
    }

    /**
     * log in with post data
     */
    private function dologinWithPostData()
    {
        // check login form contents
        if (empty($_POST['user_email'])) {
            debug("No email.");
            $this->errors[] = "Email field was empty.";
        } elseif (empty($_POST['user_password'])) {
            debug("No password.");
            $this->errors[] = "Password field was empty.";
        } elseif (!empty($_POST['user_email']) && !empty($_POST['user_password'])) {

            // create a database connection, using the constants from config/db.php (which we loaded in index.php)
            debug("establishing DB connection...");
            $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            debug("DB connection established. Setting charset...");

            // change character set to utf8 and check it
            if (!$this->db_connection->set_charset("utf8mb4")) {
                debug("charset error.");
                $this->errors[] = $this->db_connection->error;
            }

            debug("charset set. checking for errors...");

            // if no connection errors (= working database connection)
            if (!$this->db_connection->connect_errno) {

                debug("Building email/password objects.");
                // escape the POST stuff
                $email = $this->db_connection->real_escape_string($_POST['user_email']);
                $password = $this->db_connection->real_escape_string($_POST['user_password']);

                // database query, getting all the info of the selected user (allows login via email address in the
                // username field)
                debug("Constructing query.");
                $sql = "SELECT email, password
                FROM Account
                WHERE email = '" . $email . "'";
                $result_of_login_check = $this->db_connection->query($sql);

                debug("Checking query results...");
                // if this user exists
                if ($result_of_login_check->num_rows == 1) {
                    debug("User found. Verifying credentials...");

                    // get result row (as an object)
                    $result_row = $result_of_login_check->fetch_object();

                    // using PHP 5.5's password_verify() function to check if the provided password fits
                    // the hash of that user's password
                    // if (password_verify($_POST['user_password'], $result_row->user_password_hash)) {
                    
                    // but this is an insecure hack to see if it works at all
                    if ($password == $result_row->password) {
                        debug("Login successful.");
                        // write user data into PHP SESSION (a file on your server)
                        $_SESSION['email'] = $result_row->email;
                        $_SESSION['login_status'] = 1;

                    } else {
                        debug("Incorrect password.");
                        $this->errors[] = "Wrong password. Try again.";
                    }
                } else {
                    debug("User not found.");
                    $this->errors[] = "This user does not exist.";
                }
            } else {
                debug("DB connection issue.");
                $this->errors[] = "Database connection problem.";
            }
        }
    }

    /**
     * perform the logout
     */
    public function doLogout()
    {
        debug("Logging out.");
        // delete the session of the user
        $_SESSION = array();
        session_destroy();
        // return a little feeedback message
        $this->messages[] = "You have been logged out.";

    }

    /**
     * simply return the current state of the user's login
     * @return boolean user's login status
     */
    public function isUserLoggedIn()
    {
        debug("Checking if logged in:");
        if (isset($_SESSION['login_status']) AND $_SESSION['login_status'] == 1) {
            debug("I am.");
            return true;
        }
        debug("I'm not.");
        // default return
        return false;
    }
}
