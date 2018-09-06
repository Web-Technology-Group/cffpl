<!-- The registration page for the CFFPL application -->
<?php

require_once('classes\playerarea\RegistrationFormValidator.php');
require_once('classes\playerarea\RegistrationDAOValidator.php');

    // Reset the fields
    $username = '';
    $password = '';
    $confirmpassword = '';
    $firstname = '';
    $middlename = '';
    $surname = '';

    // check that the form has been submitted
    if (isset($_POST['submit'])) {

        // Re-fill the form with previous values
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirmpassword = $_POST['confirmpassword'];
        $firstname = $_POST['firstname'];
        $middlename = $_POST['middlename'];
        $surname = $_POST['surname'];

        // Create a validator object that validates the registration form
        $validator = new RegistrationFormValidator();
        $errors = $validator->validateRegistrationForm($_POST);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CFFPL - Registration Page</title>
</head>
<body>

<h2>Registration Page</h2><br>
<?php
if (!empty($errors)) {
        foreach ($errors as $key => $error) { ?>
            <p style="color:red"><?php echo $error ?></p>
<?php
        }
    } else if (isset($_POST['submit'])) {

            // Check if the user has already registered
            $registrationDAOValidator = new RegistrationDAOValidator();
            $isDuplicateUser = $registrationDAOValidator->isDuplicateUser($_POST['username']);

            if ($isDuplicateUser == true) { ?>
                <p style="color:red">
                    <?php echo "The user (". $_POST['username']. ") has already registered so please log in."?>
                    <a href="login.php">Click</a> to log in.
                </p>
            <?php
            } else {
                // Insert the new entry into the database
                $result = $registrationDAOValidator->insertRegisteredUser($_POST);
                if ($result === true) {
                    // Success
                    // The user has successfully registered so forward them to the login page so they can log in
                    header('Location: login.php');
                }
            }
        } ?>

<form method="post" action="">
    User name:<br>
    <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>"><br>
    Password:<br>
    <input type="text" name="password" value="<?php echo htmlspecialchars($password); ?>"><br>
    Confirm Password:<br>
    <input type="text" name="confirmpassword" value="<?php echo htmlspecialchars($confirmpassword); ?>"><br>
    <p>* The password must conform to complexity rules (i.e a mixture of uppercase, and lowercase letters,
        as well as numbers and/or special characters) and be a minimum of eight characters in length.<br></p>

    First name:<br>
    <input type="text" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>"><br>
    Middle Name:<br>
    <input type="text" name="middlename" value="<?php echo htmlspecialchars($middlename); ?>"><br>
    Surname:<br>
    <input type="text" name="surname" value="<?php echo htmlspecialchars($surname); ?>"><br>


    <input type="submit" name="submit" value="Register">
</form>

</body>
</html>

<?php ?>