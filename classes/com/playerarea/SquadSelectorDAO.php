<?php

namespace Com\PlayerArea\Validation;

use Com\PlayerArea\Database;

require_once('database\DBConnection.php');

class SquadSelectorDAO
{
    function isSquadSelected($username)
    {

        $result = false;

        $dbPDOConnection = Database\DBConnection::getPDOInstance();

        $statement = $dbPDOConnection->query(
            "SELECT us.userid FROM usersquads us, users u WHERE u.username = '$username' AND u.id = us.userid");

        // If the number of rows returned is greater than zero then return true, else return false.
        if ($row = $statement->rowCount() > 0) {
            $result = true;
        }

        return $result;
    }

    function getAllSquadPlayersByPosition($position)
    {

        $allSquadGKs = array();

        $dbPDOConnection = Database\DBConnection::getPDOInstance();

        $statement = $dbPDOConnection->query(
            "SELECT id, name, team, points, cost FROM premierplayers WHERE position = '$position'");

        // Build the result array to contain the relevant fields used in the front end
        while ($row = $statement->fetch()) {

            $allSquadGKs[$row[0]] = array($row[1], $row[2], $row[3], $row[4]);
        }

        return $allSquadGKs;

    }

    function submitSquad($username)
    {

        session_start();


        $dbPDOConnection = Database\DBConnection::getPDOInstance();

        try {
            $statement = $dbPDOConnection->query(
                "SELECT id FROM users WHERE username = '$username'");

            $userSquad = $_SESSION['userSquad'];

            // Build the user squad ids
            $playerids = array();

            foreach ($userSquad as $playerid) {
                array_push($playerids, $playerid);
            }

            if ($row = $statement->fetch()) {

                $insertStatement = $dbPDOConnection->prepare("INSERT INTO usersquads (userid, playerid) VALUES (?, ?) ");
                echo "2=In loop=". $row[0] ."<br>";

                // Begin the insert process
                foreach ($playerids as $playerid) {
                    $insertStatement->execute([$row[0], $playerid]);
                    echo "3=In loop=". $playerid ."<br>";
                }
            }

        } catch (\PDOException $e) {
            die("PDO Exception=" . $e->getMessage());
        }


        /*  foreach ($userSquad as $id) {
             echo "The id is: ". $id. " <br>";
         } */


        // $_SESSION['currentSquadCost'];

        // $_SESSION['username']

        /**  $dbPDOConnection $dbPDOConnection = Database\DBConnection::getPDOInstance();
         *
         * $session['userSquad'];
         * $session['currentSquadCost'];
         *
         * // Translate this into team ids (or change the db so the name is the pk?)
         * // I.e. each player has an id, from which you get the team, and from the team can
         * // get the team name
         * $premierLeagueTeamsSelectedFrom = $session['premierLeagueTeamsSelectedFrom'];
         * die($session['userSquad']);
         *
         *
         * // Close the connection
         * $dbConnection->closeDatabaseConnection($connection); */

    }

}