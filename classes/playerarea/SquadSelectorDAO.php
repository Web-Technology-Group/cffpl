<?php

require_once('DBConnection.php');

class SquadSelectorDAO
{
    function isSquadSelected($username) {

        $result = false;

        $dbConnection = new DBConnection();
        $connection = $dbConnection->getDatabaseConnection();

        // SELECT us.userid FROM usersquads us, users u
        // WHERE u.username = 'bob' AND u.id = us.userid;
        $results = $connection->query(
            "SELECT us.userid FROM usersquads us, users u WHERE u.username = '$username' AND u.id = us.userid");

        // If the number of rows returned is greater than zero then return true, else return false.
        if ($results->num_rows > 0) {
            $result = true;
        }

        // Close the connection
        $dbConnection->closeDatabaseConnection($connection);

        return $result;
    }

    function getAllSquadPlayersByPosition($position) {

        $allSquadGKs = array();

        $dbConnection = new DBConnection();
        $connection = $dbConnection->getDatabaseConnection();

        $results = $connection->query(
            "SELECT id, name, team, points, cost FROM premierplayers WHERE position = '$position'");



        // Close the connection
        $dbConnection->closeDatabaseConnection($connection);

        // Build the result array to contain the relevant fields used in the front end
        while ($row = $results->fetch_row()) {

            $allSquadGKs[$row[0]] = array($row[1], $row[2], $row[3], $row[4]);
        }

        return $allSquadGKs;

    }

}