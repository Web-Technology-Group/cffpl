<?php

namespace Com\PlayerArea;

use Com\PlayerArea\Database;

require_once('database\DBConnection.php');
require_once('CalculationEngineDAO.php');

/**
 * Calculation Engine that calculates the following:
 * - The weekly points total accumulated for a given user team
 * - The total points total (i.e. each weekly selected team combined total)
 * Class CalculationEngine
 * @package Com\PlayerArea\Validation
 */
class CalculationEngine
{
    /**
     * Find the corresponding player points total in the $latestWeekPlayerScores. In other words if the player names and
     * team match then return the corresponding player points total
     * @param $latestWeekPlayerScores
     * @param $playerName
     * @param $team
     */
    private function findPlayerPointsTotal($latestWeekPlayerScores, $playerName, $team) {

        foreach ($latestWeekPlayerScores as $id => $playerInfo) {
            // Name, team, points: $row[1], $row[2], $row[3]);
            $latestPlayerName = $playerInfo[0];
            $latestTeam = $playerInfo[1];
            if (($playerName == $latestPlayerName) && ($team == $latestTeam) ) {
                // We have a match so return the points value
                return $playerInfo[2];
            }
        }
    }

    /**
     * Calculate the weekly user score given the team that were selected
     * @param $username
     * @param null $week
     * @param bool $latestWeek
     */
    public static function calculateWeeklyUserScore($username, $week = null, $latestWeek = true) {

        // If the week is set then take that given integer value and find out the difference between
        // that week and the previous week's score
        if (isset($week)) {

        } else {
            // Get the value for latestWeek (which defaults to true, if not explicitly to set)
            if ($latestWeek) {
                // Get the latest week from the database
                $dbPDOConnection = Database\DBConnection::getPDOInstance();

                // First establish what the latest week is
                $statement = $dbPDOConnection->query("SELECT MAX(week) FROM premierplayers");
                $maxWeekValue = 0;
                if ($row = $statement->fetch()) {
                    $maxWeekValue = $row[0];
                }

                // Sanity check, in case the user hasn't selected a team for the maximum week yet.
                // Need to check that a team has been selected by the user for that week, if not then decrement the
                // count to the previous week
                $statement = $dbPDOConnection->query(
                    "SELECT pp.id FROM premierplayers pp, usersquads us, users u WHERE pp.id = us.playerid ".
                    "AND u.id = us.userid AND u.username ='$username' AND pp.week = '$maxWeekValue'");

                // If there are no rows returned then try again after decrementing the count: i.e. the previous week
                if (!($row = $statement->fetch())) {
                    $maxWeekValue = $maxWeekValue - 1;
                }

                // Get all the points associated with the players selected in the team for the max week
                // (or max week - 1). Store the results in an array
                $latestWeekPlayerScores = array();

                $statement = $dbPDOConnection->query(
                    "SELECT pp.id, pp.name, pp.team, pp.points FROM premierplayers pp, usersquads us, users u ".
                    "WHERE us.inteam = 1 ".
                    "AND pp.id = us.playerid ".
                    "AND u.id = us.userid ".
                    " AND u.username ='$username' AND pp.week = '$maxWeekValue'");


                while ($row = $statement->fetch()) {
                    //die("id=". $row['id']. ", and name=". $row['name']. ", team=". $row['team']. " and points=". $row['points']);
                    array_push($latestWeekPlayerScores, array($row['name'], $row['team'], $row['points']));

                }

                // Note, that if the maximum/requested week is already at 1, then there is no week '0'
                if ($maxWeekValue == 1) {
                    // Given that there is only the one week, take those player scores and calculate the
                    // total for that given week
                    $weeklyUserScore = 0;
                    foreach ($latestWeekPlayerScores as $id => $nameTeamPoints) {
                        $weeklyUserScore = $weeklyUserScore + $nameTeamPoints[2];
                    }

                    // Return a results array with the username, weekly points score, and for the given week
                    return array($username, $maxWeekValue, $weeklyUserScore);

                } else {

                    $previousWeek = $maxWeekValue - 1;
                    $userWeeklyScoreGrandTotal = 0;
                    foreach ($latestWeekPlayerScores as $id => $nameTeamPoints) {
                        // The player's name
                        $playerName = $nameTeamPoints['name'];

                        // The player's team
                        $team = $nameTeamPoints['team'];

                        // The points total for that player
                        $points = $nameTeamPoints['points'];

                        // Get the number of points for that particular player for the previous week
                        $selectStatement = $dbPDOConnection->query(
                            "SELECT DISTINCT pp.points, FROM premierplayers pp, usersquads us, users u ".
                            "WHERE pp.name = ? AND  pp.team = ? AND u.id = us.userid AND u.username = ? ".
                            "AND pp.week = ?");

                        $selectStatement->execute([$playerName, $team, $username, $previousWeek]);

                        // Get the value from the statement and work out the previous value
                        while ($row = $selectStatement->fetch()) {
                            $previousWeekPointsScore = $row['points']; // The points total for the previous week

                            // The total for the latest/requested week
                            $latestPointsScoreForPlayer =
                                CalculationEngine::findPlayerPointsTotal($latestWeekPlayerScores, $playerName, $team);

                            // The point change is therefore the latest points score minus the previous
                            // weeks points score
                            $weeklyPointsTotalForPlayer = $latestPointsScoreForPlayer - $previousWeekPointsScore;

                            // Add this to the grand total weekly score i.e. the combined weekly score for those
                            // selected players in the team
                            $userWeeklyScoreGrandTotal += $weeklyPointsTotalForPlayer;
                        }
                    }
                }

                // Get all the previous week's points which were associated with the players selected
                // before the maximum week itself.
                // Store the results in an array
                $previousWeekPlayerScores = array();

                $weeklyPointsTotalForPlayer = 0;
                $previousWeek = $maxWeekValue - 1;
                $userWeeklyScoreGrandTotal = 0;
                foreach ($latestWeekPlayerScores as $id => $nameTeamPoints) {
                    // The player's name
                    $playerName = $nameTeamPoints[0];

                    // The player's team
                    $team = $nameTeamPoints[1];

                    // The points total for that player
                    $points = $nameTeamPoints[2];

                    // Get the number of points for that particular player for the previous week
                    $selectStatement = $dbPDOConnection->query(
                        "SELECT DISTINCT pp.points, FROM premierplayers pp, usersquads us, users u ".
                                  "WHERE pp.name = ? AND  pp.team = ? AND u.id = us.userid AND u.username = ? ".
                                  "AND pp.week = ?");

                    $selectStatement->execute([$playerName, $team, $username, $previousWeek]);

                    // Get the value from the statement and work out the previous value
                    while ($row = $selectStatement->fetch()) {
                        $previousWeekPointsScore = $row[0]; // The points total for the previous week

                        // The total for the latest/requested week
                        $latestPointsScoreForPlayer =
                            CalculationEngine::findPlayerPointsTotal($latestWeekPlayerScores, $playerName, $team);

                        // The point change is therefore the latest points score minus the previous
                        // weeks points score
                        $weeklyPointsTotalForPlayer = $latestPointsScoreForPlayer - $previousWeekPointsScore;

                        // Add this to the grand total weekly score i.e. the combined weekly score for those
                        // selected players in the team
                        $userWeeklyScoreGrandTotal += $weeklyPointsTotalForPlayer;
                    }
                }

                // Return a results array with the username, weekly points score, and for the given week
                return array($username, $maxWeekValue, $userWeeklyScoreGrandTotal);
            }
        }

    }

    /**
     * Get the leaderboard which calculates the sum of all user weekly scores
     */
    public static function getLeaderboard() {

        $dbPDOConnection = Database\DBConnection::getPDOInstance();
        $selectStatement = $dbPDOConnection->query(
            "SELECT u.username, FORMAT(SUM(weekpointstotal), 2) AS total FROM userweeklyscores uws, users u ".
            "WHERE uws.userid = u.id ".
            "GROUP BY username ".
            "ORDER BY FORMAT(SUM(weekpointstotal), 2) DESC");

        $leaderboard = array();
        while ($row = $selectStatement->fetch()) {
            $row[0]; // total points
            $row[1]; // username

            $leaderboard[$row[1]] = $row[0];
        }

        return $leaderboard;
    }


    public static function generateAndInsertAllUserWeeklyScores() {

        // For each user in the system calculate the weekly scores
        $dbPDOConnection = Database\DBConnection::getPDOInstance();

        $selectStatement = $dbPDOConnection->query("SELECT username FROM users");
        while ($row = $selectStatement->fetch()) {
            $dataToInsert = CalculationEngine::calculateWeeklyUserScore($row['username'], null, true);
            CalculationEngineDAO::insertUserWeeklyScoreEntry($dataToInsert);

        }


    }
}