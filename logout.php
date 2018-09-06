<!-- The login page for the CFFPL application -->
<?php

session_start();
$username = $_SESSION['username'];

if (!$username) {
    // Redirect to the login page as the username is not present so therefore the
    // user has not logged in
    header('Location: ../login.php');
}

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CFFPL - Logout Page</title>
</head>
<body>
<h2>Logout Page</h2><br>

    <h5>You have successfully logged out.</h5>

</body>
</html>

<?php ?>
