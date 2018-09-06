<!-- squad selection 1 (Goalkeepers) -->
<?php

require_once('..\classes\playerarea\SquadSelectorDAO.php');
require_once('..\classes\playerarea\SquadSelectorValidator.php');

session_start();
$username = $_SESSION['username'];

if (!$username) {
    // Redirect to the login page as the username is not present so therefore the
    // user has not logged in
    header('Location: ../login.php');
}

$squadSelector = new SquadSelectorDAO();
$allGKs = $squadSelector->getAllSquadPlayersByPosition('G');

// check that the form has been submitted
if (isset($_POST['submit'])) {

    // Create a validator object that validates the goalkeeping squad form submission
    $validator = new SquadSelectorValidator();
    $errors = $validator->validateGKSquadForm($_POST, $_SESSION);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CFFPL - Player Area - Goalkeepers Squad Selection</title>
</head>
<body>

    <h4>Please only select two goalkeepers for the squad, <?php echo $username ?></h4>

    <!-- Insert rules of the squad selection using an include file -->
    <?php include 'competitionrules.html';?>
    <?php
    if (!empty($errors)) {
        foreach ($errors as $key => $error) { ?>
            <p style="color:red"><?php echo $error ?></p>
    <?php
        }
    } else if (isset($_POST['submit'])) {
            echo "Success";
            $results = $validator->getGKSelectionForSession($_POST);
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
                        <input type="checkbox" name="player[]" value="<?php echo "$key" ?>">
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
            <input type="submit" name="submit" value="Submit Goalkeepers">
        </form>
</body>
</html>

