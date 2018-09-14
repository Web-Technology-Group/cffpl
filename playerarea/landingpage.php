<?php

namespace PlayerArea;

use Com\PlayerArea\Validation;

require_once('..\classes\com\playerarea\SquadSelectorDAO.php');

session_start();
$username = $_SESSION['username'];

if (!$username) {
    // Redirect to the login page as the username is not present so therefore the
    // user has not logged in
    header('Location: ../login.php');
}

$squadSelector = new Validation\SquadSelectorDAO();
$isSquadSelected = $squadSelector->isSquadSelected($username);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CFFPL - Player Area Home Page</title>
</head>
<body>
    <h2>Player Area Home Page</h2><br>

    <h4>Welcome <?php echo $username ?>!</h4>
    <br>
    <h5><?php if (!$isSquadSelected) { ?>
            <a href="squadselection1.php">Select Squad</a>
        <?php   } else { ?>
            <a href="teamselection1.php">Select Team</a>
        <?php   } ?></h5>
    <br>
    <h5><a href="../logout.php">Logout</a></h5>


</body>
</html>
