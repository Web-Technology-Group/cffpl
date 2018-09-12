<?php

require_once('DBConnection.php');

class PremierTeamLoader
{
    static function getPremierTeamNames() {

        $dbConnection = new DBConnection();
        $connection = $dbConnection->getDatabaseConnection();

        $results = $connection->query("SELECT name FROM premierteams");

        $premierteams = array();
        while ($row = $results->fetch_assoc()) {
            array_push($premierteams, $row['name']);
        }

        // Close the connection
        $dbConnection->closeDatabaseConnection($connection);

        return $premierteams;
    }

    static function getPremierLeagueTeams() {

        $dbConnection = new DBConnection();
        $connection = $dbConnection->getDatabaseConnection();

        $results = $connection->query("SELECT id, name FROM premierteams");

        // $premierLeagueTeam = '';
        $premierTeams = array();
        while ($row = $results->fetch_assoc()) {
            $premierLeagueTeam = new PremierTeam();
            // die("1* in assoc sql getPremierLeagueTeams end.");
            $premierLeagueTeam->setId($row['id']);
            $premierLeagueTeam->setName($row['name']);

            array_push($premierTeams, $premierLeagueTeam);
        }
        die(" sql getPremierLeagueTeams end..". var_dump($premierTeams));



        // Close the connection
        $dbConnection->closeDatabaseConnection($connection);

        return $premierTeams;
    }


}