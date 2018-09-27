<?php

namespace View\PlayerArea;

use Com\PlayerArea;

require_once('..\classes\com\playerarea\TeamSelectorDAO.php');

session_start();
$username = $_SESSION['username'];

if (!$username) {
    // Redirect to the login page as the username is not present so therefore the
    // user has not logged in
    header('Location: ../login.php');
}

$teamSelectorDAO = new PlayerArea\TeamSelectorDAO();
$teamSelectorDAO->submitTeam($username);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CFFPL - Player Area - Team Selection Complete</title>
</head>
<body>
<h3>Team Selection Complete!</h3>
<br>
<h3>Please return to the home page by clicking <a href="landingpage.php">here</a></h3>
</body>
</html>