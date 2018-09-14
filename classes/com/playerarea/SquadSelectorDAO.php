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

                $insertStatement = $dbPDOConnection->prepare(
                    "INSERT INTO usersquads (userid, playerid) VALUES (?, ?) ");

                // Begin the insert process
                foreach ($playerids as $playerid) {
                    $insertStatement->execute([$row[0], $playerid]);
                }
            }

        } catch (\PDOException $e) {
            die("PDO Exception=" . $e->getMessage());
        }
    }

}