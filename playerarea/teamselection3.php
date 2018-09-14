<?php

namespace PlayerArea;

use Com\PlayerArea\Validation;

require_once('..\classes\com\playerarea\TeamSelectorDAO.php');
require_once('..\classes\com\playerarea\TeamSelectorValidator.php');

session_start();
$username = $_SESSION['username'];

if (!$username) {
    // Redirect to the login page as the username is not present so therefore the
    // user has not logged in
    header('Location: ../login.php');
}

$teamSelector = new Validation\TeamSelectorDAO();
$allMidfielders = $teamSelector->getAllTeamPlayersByPosition('M', $username);

$validator = new Validation\TeamSelectorValidator();

// check that the form has been submitted
if (isset($_POST['submit'])) {

    // Create a validator object that validates the midfielders squad form submission
    $errors = $validator->validateMidfieldersTeamForm($_POST);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CFFPL - Player Area - Midfielders Team Selection</title>
</head>
<body>
<br>
<h4>Please only select four midfielders for the team, <?php echo $username ?></h4>

<!-- Insert rules of the squad selection using an include file -->
<?php include 'competitionrules.html';?>
<?php
if (!empty($errors)) {
    foreach ($errors as $key => $error) { ?>
        <p style="color:red"><?php echo $error ?></p>
        <?php
    }
} else if (isset($_POST['submit'])) {
    header("Location: teamselection4.php");
} ?>
<br>
<form method="post" action="">
    <table>
        <tr>
            <td>Select</td><td>Player</td><td>Team</td><td>Points</td>
        </tr>
        <?php
        foreach ($allMidfielders as $key => $valMid) { ?>
        <tr>
            <td>
                <input type="checkbox" name="player[]" value="<?php echo "$key" ?>">
            </td>
            <td>
                <?php echo "$valMid[0]" ?>
            </td>
            <td>
                <?php echo "$valMid[1]" ?>
            </td>
            <td>
                <?php echo "$valMid[2]" ?><br>
            </td>
        </tr>
    </table>
    <?php
    }
    ?>
    <br>
    <input type="submit" name="submit" value="Submit Midfielders">
</form>
</body>
</html>
