<?php

namespace View\PlayerArea;

use Com\PlayerArea;

require_once('..\classes\com\playerarea\TeamSelectorDAO.php');
require_once('..\classes\com\playerarea\TeamSelectorValidator.php');

session_start();
$username = $_SESSION['username'];

if (!$username) {
    // Redirect to the login page as the username is not present so therefore the
    // user has not logged in
    header('Location: ../login.php');
}

$teamSelector = new PlayerArea\TeamSelectorDAO();
$allAttackers = $teamSelector->getAllTeamPlayersByPosition('A', $username);

$validator = new PlayerArea\TeamSelectorValidator();

// check that the form has been submitted
if (isset($_POST['submit'])) {

    // Create a validator object that validates the attackers squad form submission
    $errors = $validator->validateAttackersTeamForm($_POST);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CFFPL - Player Area -Attackers Team Selection</title>
</head>
<body>
<br>
<h4>Please only select two attackers for the team, <?php echo $username ?></h4>

<!-- Insert rules of the squad selection using an include file -->
<?php include 'competitionrules.html';?>
<?php
if (!empty($errors)) {
    foreach ($errors as $key => $error) { ?>
        <p style="color:red"><?php echo $error ?></p>
        <?php
    }
} else if (isset($_POST['submit'])) {
    header("Location: teamselectioncomplete.php");
} ?>
<br>
<form method="post" action="">
    <table>
        <tr>
            <td>Select</td><td>Player</td><td>Team</td><td>Points</td>
        </tr>
        <?php
        foreach ($allAttackers as $key => $valAtt) { ?>
        <tr>
            <td>
                <input type="checkbox" name="player[]" value="<?php echo "$key" ?>">
            </td>
            <td>
                <?php echo "$valAtt[0]" ?>
            </td>
            <td>
                <?php echo "$valAtt[1]" ?>
            </td>
            <td>
                <?php echo "$valAtt[2]" ?><br>
            </td>
        </tr>
    </table>
    <?php
    }
    ?>
    <br>
    <input type="submit" name="submit" value="Submit Attackers">
</form>
</body>
</html>
