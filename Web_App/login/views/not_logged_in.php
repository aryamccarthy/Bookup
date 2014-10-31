<?php
// show potential errors / feedback (from login object)
if (isset($login)) {
    if ($login->errors) {
        foreach ($login->errors as $error) {
            echo $error;
        }
    }
    if ($login->messages) {
        foreach ($login->messages as $message) {
            echo $message;
        }
    }
}
?>

<!-- login form box -->
<form method="post" action="index.php" name="loginform">

    <label for="login_input_email">Email</label>
    <input id="login_input_email" class="login_input" type="text" name="user_email" required />

    <label for="login_input_password">Password</label>
    <input id="login_input_password" class="login_input" type="password" name="user_password" autocomplete="off" required />

    <input type="submit"  name="login" value="Log in" />

</form>

<a href="register.php">Register new account</a>
