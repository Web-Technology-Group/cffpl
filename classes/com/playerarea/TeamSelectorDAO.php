<?php

namespace Com\PlayerArea;

use Com\PlayerArea\Database;

require_once('database\DBConnection.php');

/**
 * Class responsible for the selection and submission of a user's team
 *
 * Class TeamSelectorDAO
 * @package Com\PlayerArea\Validation
 */
class TeamSelectorDAO
{
    /**
     * Get all the team players within the squad by position. For now this defaults to the latest week only
     * @param $position
     * @param $username
     * @return array
     */
    public function getAllTeamPlayersByPosition($position, $username) {

        $allAvailableTeamPlayers = array();

        $dbPDOConnection = Database\DBConnection::getPDOInstance();

        /* // This should ideally be incorporated in the SQL queries run..
        $statement = $dbPDOConnection->query("SELECT MAX(week) FROM premierplayers");
        $maxWeekValue = 0;
        while ($row = $statement->fetch()) {
            $maxWeekValue = $row[0];

            // Now plug that MAX week value into the main SQL query so that we get the fields
            // from premierplayers only for the relevant position and for the given week (to avoid duplicate
            // players being returned)
        } */

        try {
            // For now revert to just the values for week 1 - even though the points will not be the most up to date
            // In an ideal scenario, it would establish what the latest week is, and plug that max week value into
            // into the main SQL query so that we get the field from premierplayers only for the relevant position
            // and for the given week (to avoid duplicate players being returned)
            $statement = $dbPDOConnection->query(
                "SELECT pp.id, pp.name, pp.team, pp.points FROM premierplayers pp, usersquads us, users u ".
                        "WHERE position = '$position' AND pp.id = us.playerid AND u.id = us.userid".
                        " AND u.username ='$username' AND pp.week = 1");

            // Build the result array to contain the relevant fields used in the front end
            while ($row = $statement->fetch()) {

                $allAvailableTeamPlayers[$row[0]] = array($row[1], $row[2], $row[3]);
            }

            return $allAvailableTeamPlayers;

            } catch (\PDOException $e) {
            echo "An exception has occurred. ". $e->getMessage(). ". Please notify the help desk.";
        }
    }

    /**
     * Submit the team selected by the user
     *
     * @param $username
     */
    public function submitTeam($username)
    {

        session_start();


        $dbPDOConnection = Database\DBConnection::getPDOInstance();

        try {
            $userTeam = $_SESSION['userTeam'];
            // Firstly, simply deselect every member of the team for that user
            $insertStatement = $dbPDOConnection->prepare(
            "UPDATE usersquads  us, users u SET us.inteam = 0  WHERE us.userid = u.id ".
                "AND u.username = '$username'");
            $insertStatement->execute();

            // Note, you cannot bind multiple values to a single named parameter in,
            // for example, the IN() clause of an SQL statement. This appears to be the only alternative
            foreach ($userTeam as $key => $playerId) {
                $sql = "UPDATE usersquads us, users u SET us.inteam = 1 WHERE us.userid = u.id AND u.username = ? AND us.playerid = ?";
                $stm = $dbPDOConnection->prepare($sql);
                $stm->execute([$username, $playerId]);
            }

        } catch (\PDOException $e) {
            echo "An exception has occurred. ". $e->getMessage(). ". Please notify the help desk.";
        }
    }

    /**
     * Determine whether the user has selected their team.
     * @param $username
     * @return bool
     */
    public function isTeamSelected($username)
    {
        $result = false;

        $dbPDOConnection = Database\DBConnection::getPDOInstance();

        $statement = $dbPDOConnection->query(
            "SELECT us.userid FROM usersquads us, users u WHERE u.username = '$username' AND u.id = us.userid AND us.inteam = 1");

        // If the number of rows returned is greater than zero then return true, else return false.
        if ($row = $statement->rowCount() > 0) {
            $result = true;
        }

        return $result;
    }

    /**
     * Get the player information given the username i.e. the current team selected by the user
     * @param $userTeam
     */
    public static function getPlayerInfoByUsername($username) {

        $dbPDOConnection = Database\DBConnection::getPDOInstance();

        $playerNames = array();

        $statement = $dbPDOConnection->query(
            "SELECT pp.name FROM premierplayers pp, usersquads us, users u ".
            "WHERE u.username = '$username' AND u.id = us.userid AND us.playerid = pp.id AND us.inteam = 1");

        while ($row = $statement->fetch()) {
            $name = $row[0];
            array_push($playerNames, $name);
        }

        return $playerNames;
    }

    /**
     * Check whether the team selection window is currently
     */
    public static function isTeamSelectionWindowOpen() {
        // Get the current time
        $currentDateTime = new \DateTime("now");

        // Get all the dates from the weekly history table
        $dbPDOConnection = Database\DBConnection::getPDOInstance();
        $statement = $dbPDOConnection->query(
            "SELECT weekstarttime, weekendtime FROM weeklyhistory");

        $windowOpen = false;
        while ($row = $statement->fetch()) {
            $weekstarttime = new \DateTime($row['weekstarttime']);
            $weekendtime = new \DateTime($row['weekendtime']);

            if (($currentDateTime >= $weekstarttime) &&
                ($currentDateTime <= $weekendtime)) {
                return true;
            }
        }
        return false;
    }
}