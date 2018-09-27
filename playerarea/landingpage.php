<?php

namespace View\PlayerArea;

use Com\PlayerArea;

require_once('..\classes\com\playerarea\SquadSelectorDAO.php');
require_once('..\classes\com\playerarea\TeamSelectorDAO.php');
require_once('..\classes\com\playerarea\CalculationEngine.php');

session_start();
$username = $_SESSION['username'];

if (!$username) {
    // Redirect to the login page as the username is not present so therefore the
    // user has not logged in
    header('Location: ../login.php');
}

$squadSelector = new PlayerArea\SquadSelectorDAO();
$isSquadSelected = $squadSelector->isSquadSelected($username);

$teamSelector = new PlayerArea\TeamSelectorDAO();
$isTeamSelected = $teamSelector->isTeamSelected($username);

$leaderboardArray = PlayerArea\CalculationEngine::getLeaderboard();

$teamSelectionWindowOpen =  PlayerArea\TeamSelectorDAO::isTeamSelectionWindowOpen();

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
    <br />
    <?php if (!$isSquadSelected) { ?>
            <a href="squadselection1.php">Select Squad</a>
        <?php   } else {

            if ($teamSelectionWindowOpen) {

            // To Do:
            // Only allow the user to select the team if the selection window is still open for the latest week
            // Put something that hides this element if the window to select the team is not open
            // i.e. if current time is after/before a certain time

                ?>
                <a href="teamselection1.php">Select Team</a>
                <br/>
            <?php } ?>


            <?php if ($isTeamSelected) { ?>
                <!-- Echo out the current team -->
                <h4>The current team is:</h4><br/>
                <?php
                    $playerNames = PlayerArea\TeamSelectorDAO::getPlayerInfoByUsername($username);
                    foreach ($playerNames as $playerName) { ?>
                        <?php echo $playerName ?>
                 <?php   }
            } ?>

        <?php } ?>
    <br/>

    <!-- Show the overall leader board and the user's position on that leader board i.e. with their total points -->
    <h4>Leaderboard<br/>
    <?php
        $positionalCount = 1;
        foreach ($leaderboardArray as $element => $value) {
            echo $positionalCount. ") ". $value. " with total points: ". $element. "<br />";
            $positionalCount = $positionalCount + 1;
        }
    ?></h4>
    <!-- Maybe show the user's current team if selected -->
    <h5><a href="../logout.php">Logout</a></h5>


</body>
</html>
