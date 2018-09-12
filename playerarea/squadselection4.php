<?php

namespace PlayerArea;

use Com\PlayerArea\Validation;

require_once('..\classes\com\playerarea\SquadSelectorDAO.php');
require_once('..\classes\com\playerarea\SquadSelectorValidator.php');

session_start();
$username = $_SESSION['username'];

if (!$username) {
    // Redirect to the login page as the username is not present so therefore the
    // user has not logged in
    header('Location: ../login.php');
}

$squadSelector = new Validation\SquadSelectorDAO();
$allGKs = $squadSelector->getAllSquadPlayersByPosition('A');

// Get the stauts so far
$currentSquadCost = $_SESSION['currentSquadCost'];
$userSquad = $_SESSION['userSquad'];

// check that the form has been submitted
if (isset($_POST['submit'])) {

    // Create a validator object that validates the attackers squad form submission
    $validator = new Validation\SquadSelectorValidator();
    $errors = $validator->validateAttackersSquadForm($_POST);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CFFPL - Player Area - Attackers Squad Selection</title>
</head>
<body>
<h4>Please only select three attackers for the squad, <?php echo $username ?></h4>
<br>
<!-- Insert rules of the squad selection using an include file -->
<?php include 'competitionrules.html';?>
<br>
<h3>Current squad value: £<?php echo "$currentSquadCost" ?>m</h3><br>
<br>
<?php
if (!empty($errors)) {
    foreach ($errors as $key => $error) { ?>
        <p style="color:red"><?php echo $error ?></p>
        <?php
    }
} else if (isset($_POST['submit'])) {
    echo "Success";
    header("Location: squadselectioncomplete.php");
} ?>
<br>
<form method="post" action="">
    <table>
        <tr>
            <td>Select</td><td>Player</td><td>Team</td><td>Points</td><td>Cost</td>
        </tr>
        <?php
        foreach ($allGKs as $key => $valGK) { ?>
        <tr>
            <td>
                <input type="checkbox" name="player[]" value="<?php echo "$key". "&team="."$valGK[1]". "&cost="."$valGK[3]" ?>">
            </td>
            <td>
                <?php echo "$valGK[0]" ?>
            </td>
            <td>
                <?php echo "$valGK[1]" ?>
            </td>
            <td>
                <?php echo "$valGK[2]" ?>
            </td>
            <td>
                £<?php echo "$valGK[3]" ?> m<br>
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