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

$squadSelectorDAO = new Validation\SquadSelectorDAO();
$squadSelectorDAO->submitSquad($username);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CFFPL - Player Area - Squad Selection Complete</title>
</head>
<body>
    <h3>Squad Selection Complete!</h3>
    <br>
    <h3>Please <a href="teamselection1.php">select team</a></h3>
</body>
</html>