<?php

namespace Com\PlayerArea\Validation;

use Com\PlayerArea\Database;

require_once('database\DBConnection.php');


class TeamSelectorDAO
{
    function getAllTeamPlayersByPosition($position, $username) {

        $allAvailableTeamPlayers = array();

        $dbPDOConnection = Database\DBConnection::getPDOInstance();

        try {
            $statement = $dbPDOConnection->query(
                "SELECT pp.id, pp.name, pp.team, pp.points FROM premierplayers pp, usersquads us, users u ".
                        "WHERE position = '$position' AND pp.id = us.playerid AND u.id = us.userid".
                        " AND u.username ='$username'");

            // Build the result array to contain the relevant fields used in the front end
            while ($row = $statement->fetch()) {

                $allAvailableTeamPlayers[$row[0]] = array($row[1], $row[2], $row[3]);
            }

            return $allAvailableTeamPlayers;

            } catch (\PDOException $e) {
            die("PDO Exception=" . $e->getMessage());
        }
    }

    function submitTeam($username)
    {

        session_start();


        $dbPDOConnection = Database\DBConnection::getPDOInstance();

        try {
            $userTeam = $_SESSION['userTeam'];
            //die("The first id is=".$userTeam[0]);
            /* // Firstly, simply deselect every member of the team for that user
            $insertStatement = $dbPDOConnection->prepare(
            "UPDATE usersquads  us, users u SET us.inteam = 0  WHERE us.userid = u.id ".
                "AND u.username = '$username'");
            $insertStatement->execute(); */

            $in  = str_repeat('?,', count($userTeam) - 1) . '?';
            $sql = "UPDATE usersquads us, users u SET us.inteam = 1 WHERE us.userid = u.id AND u.username = '$username' AND us.playerid IN('$in')";
            $stm = $dbPDOConnection->prepare($sql);
            $stm->execute($userTeam);


            /* // Then, submit the new team
            /$insertStatement2 = $dbPDOConnection->prepare(
                "UPDATE usersquads us, users u SET us.inteam = 1 WHERE us.userid = u.id ".
                "AND u.username = '$username' AND us.playerid IN (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insertStatement2->execute(
                $userTeam[0], $userTeam[1], $userTeam[2], $userTeam[3],
                $userTeam[4], $userTeam[5], $userTeam[6], $userTeam[7], $userTeam[8], $userTeam[9], $userTeam[10]); */
            die("Row count affected by an update=" .$stm->rowCount());

        } catch (\PDOException $e) {
            die("PDO Exception=" . $e->getMessage());
        }
    }
}