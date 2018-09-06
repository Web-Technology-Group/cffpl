<!-- The login page for the CFFPL application -->
<?php

require_once('classes\playerarea\LoginFormValidator.php');
require_once('classes\playerarea\LoginDAOValidator.php');

// Reset the fields
$username = '';
$password = '';

// check that the form has been submitted
if (isset($_POST['submit'])) {

    // Re-fill the form with previous values
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Create a validator object that validates the login form
    $validator = new LoginFormValidator();
    $errors = $validator->validateLoginForm($_POST);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CFFPL - Login Page</title>
</head>
<body>
    <h2>Login Page</h2><br>

    <?php
    if (!empty($errors)) {
        foreach ($errors as $key => $error) { ?>
            <p style="color:red"><?php echo $error ?></p>
            <?php
        }
    } else if (isset($_POST['submit'])) {

        // Check if the user login credentials are valid
        $loginDAOValidator = new LoginDAOValidator();
        $isLoginValid = $loginDAOValidator->isLoginValid($_POST);

        if ($isLoginValid != true) { ?>
            <p style="color:red">
                <?php echo "The credentials you supplied are invalid. Please try again."?>
            </p>
            <?php
        } else {

            session_start();
            $_SESSION['username'] = $_POST['username'];
            // Success
            // The user has successfully registered so forward them to the landing page for logged in users
            header('Location: playerarea/landingpage.php');
        }


    } ?>

    <form method="post" action="">
        User name:<br>
        <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>"><br>
        Password:<br>
        <input type="text" name="password" value="<?php echo htmlspecialchars($password); ?>"><br>
        <input type="submit" name="submit" value="Login">
    </form>

</body>
</html>

<?php ?>
