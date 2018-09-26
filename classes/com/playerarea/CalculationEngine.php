<?php

namespace Com\PlayerArea;

use Com\PlayerArea\Database;

require_once('database\DBConnection.php');

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
    public function calculateWeeklyUserScore($username, $week = null, $latestWeek = true) {

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
                while ($row = $statement->fetch()) {
                    $maxWeekValue = $row[0];
                }

                // Get all the points associated with the players selected in the team for the max week.
                // Store the results in an array
                $latestWeekPlayerScores = array();

                // SELECT pp.id, pp.name, pp.team, pp.points
                // FROM premierplayers pp, usersquads us, users u
                // WHERE us.inteam = 1
                // AND pp.id = us.playerid
                // AND u.id = us.userid
                // AND u.username ='?'
                //AND pp.week = '?';
                $statement = $dbPDOConnection->query(
                    "SELECT pp.id, pp.name, pp.team, pp.points FROM premierplayers pp, usersquads us, users u ".
                    "WHERE us.inteam = 1 ".
                    "AND pp.id = us.playerid ".
                    "AND u.id = us.userid ".
                    " AND u.username ='$username' AND pp.week = '$maxWeekValue'");

                while ($row = $statement->fetch()) {

                    $latestWeekPlayerScores[$row[0]] = array($row[1], $row[2], $row[3]);
                }

                // Get all the previous week's points which were associated with the players selected
                // before the maximum week itself.
                // Store the results in an array
                $previousWeekPlayerScores = array();

                // SELECT DISTINCT pp.id, pp.name, pp.team, pp.points
                //FROM premierplayers pp, usersquads us, users u
                //WHERE pp.name = 'Vietto'
                //AND pp.team = 'Fulham'
                //-- AND pp.id = us.playerid
                //AND u.id = us.userid
                //AND u.username ='mark.lupine@civica.co.uk'
                //AND pp.week = '2';

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
                            $this->findPlayerPointsTotal($latestWeekPlayerScores, $playerName, $team);

                        // The point change is therefore the latest points score minus the previous
                        // weeks points score
                        $weeklyPointsTotalForPlayer = $latestPointsScoreForPlayer - $previousWeekPointsScore;

                        // Add this to the grand total weekly score i.e. the combined weekly score for those
                        // selected players in the team
                        $userWeeklyScoreGrandTotal += $weeklyPointsTotalForPlayer;
                    }
                }

                return $userWeeklyScoreGrandTotal;
            }
        }

    }


}