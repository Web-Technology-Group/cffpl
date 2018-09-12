<?php

class PremierPlayerLoader
{
    static function getPremierPlayers() {

        $dbConnection = new DBConnection();
        $connection = $dbConnection->getDatabaseConnection();

        $results = $connection->query("SELECT id, name, team, points, cost, position FROM premierteams");

        $premierplayers = array();
        while ($row = $results->fetch_assoc()) {
            $premierPlayer = new PremierPlayer();
            $premierPlayer->setId($row['id']);
            $premierPlayer->setName($row['name']);
            $premierPlayer->setTeam($row['team']);
            $premierPlayer->setPoints($row['points']);
            $premierPlayer->setCost($row['costs']);
            $premierPlayer->setCost($row['position']);

            array_push($premierplayers, $premierPlayer);
        }

        // Close the connection
        $dbConnection->closeDatabaseConnection($connection);

        return $premierplayers;
    }
}