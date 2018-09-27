<?php

namespace Com\PlayerArea;

use Com\PlayerArea\Database;

require_once('database\DBConnection.php');

/**
 * Class that encapsulates logic regarding the selection and submission of a user's squad.
 *
 * Class SquadSelectorDAO
 * @package Com\PlayerArea\Validation
 */
class SquadSelectorDAO
{
    /**
     * Determine whether the user has selected their squad.
     * @param $username
     * @return bool
     */
    public function isSquadSelected($username)
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

    /**
     * Get all the squad players by position. For now this defaults to the latest week only
     * @param $position
     * @param null $week
     * @param bool $latestWeek
     * @return array
     */
    public function getAllSquadPlayersByPosition($position, $week = null, $latestWeek = true)
    {
        $allSquadGKs = array();

        $dbPDOConnection = Database\DBConnection::getPDOInstance();

        // First establish what the latest week is
        $statement = $dbPDOConnection->query("SELECT MAX(week) FROM weeklyhistory");
        $maxWeekValue = 0;
        while ($row = $statement->fetch()) {
            $maxWeekValue = $row[0];

            // Now plug that MAX week value into the main SQL query so that we get the fields
            // from premierplayers only for the relevant position and for the given week (to avoid duplicate
            // players being returned)
        }

        $statement = $dbPDOConnection->query(
            "SELECT id, name, team, points, cost FROM premierplayers WHERE position = '$position' AND week = '$maxWeekValue'");

        // Build the result array to contain the relevant fields used in the front end
        while ($row = $statement->fetch()) {

            $allSquadGKs[$row[0]] = array($row[1], $row[2], $row[3], $row[4]);
        }

        return $allSquadGKs;

    }

    /**
     * Submit the squad selected
     * @param $username
     */
    public function submitSquad($username)
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
            echo "An exception has occurred. ". $e->getMessage(). ". Please notify the help desk.";
        }
    }

}