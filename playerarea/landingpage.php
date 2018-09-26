<?php

namespace PlayerArea;

use Com\PlayerArea\Validation;
use Com\PlayerArea;

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

$teamSelector = new Validation\TeamSelectorDAO();
$isTeamSelected = $teamSelector->isTeamSelected($username);

$leaderboardArray = PlayerArea\CalculationEngine::getLeaderboard();

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
        <?php   } else {

        // To Do:
        // Only allow the user to select the team if the selection window is still open for the latest week
        // Put something that hides this element if the window to select the team is not open
        // i.e. if current time is after/before a certain time

            ?>
            <a href="teamselection1.php">Select Team</a>
            <br/>
            <?php if ($isTeamSelected) { ?>
                <!-- Echo out the current team -->
                <?php
                    $userTeam = $_SESSION['userTeam'];
                    foreach ($userTeam as $player) { ?>
                        <!-- This may be an id, so have to look up the actual player name if required -->
                        <h4><? echo $player ?></h4> <br/>
                 <?php   }
            } ?>

        <?php } ?></h5>
    <br/>

    <!-- Show the overall leader board and the user's position on that leader board i.e. with their total points -->
    <?
        $positionalCount = 1;
        foreach ($leaderboardArray as $element) {
            $userOnLeaderboard = $element[0]; // username
            $userTotalPoints = $element[1]; // total points
            echo "'$positionalCount') ". $userOnLeaderboard. " with total points=". $userTotalPoints. "<br/>";
            $positionalCount++;
        }

        ?>
    <!-- Maybe show the user's current team if selected -->
    <h5><a href="../logout.php">Logout</a></h5>


</body>
</html>
