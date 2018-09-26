<?php

namespace Com\PlayerArea;

use Com\PlayerArea\Database;

require_once('database\DBConnection.php');

/**
 * Class that handles DB interaction for CRUD operations relating to user weekly scores
 *
 * Class CalculationEngineDAO
 * @package Com\PlayerArea
 */
class CalculationEngineDAO
{
    /**
     * Insert a user weekly score entry into the userweeklyscores DB table
     *
     * @param $resultsArray
     */
    public static function insertUserWeeklyScoreEntry($resultsArray) {
        // $username, $maxWeekValue, $userWeeklyScoreGrandTotal
        $username = $resultsArray[0];
        $maxWeekValue = $resultsArray[1];
        $userWeeklyScoreGrandTotal = $resultsArray[2];

        $dbPDOConnection = Database\DBConnection::getPDOInstance();

        try {
            $statement = $dbPDOConnection->query(
                "SELECT id FROM users WHERE username = '$username'");

            if ($row = $statement->fetch()) {
                $insertStatement = $dbPDOConnection->prepare(
                    "INSERT INTO userweeklyscores (userid, week, weekpointstotal) VALUES (?, ?, ?) ");

                $insertStatement->execute([$row[0], $maxWeekValue, $userWeeklyScoreGrandTotal]);
            }

        } catch (\PDOException $e) {
            echo "An exception has occurred. " . $e->getMessage() . ". Please notify the help desk.";
        }
    }

    /**
     * Insert all user weekly score entries into the required table
     * @param $resultArrays
     */
    public static function insertAllUserWeeklyScoreEntries($resultArrays) {

        // Calculate all user weekly scores and insert one per entry into the database
        foreach ($resultArrays as $resultArray => $ra) {
            $resultArray = CalculationEngine::calculateWeeklyUserScore($ra[0], $ra[1], $ra[2]);
            CalculationEngineDAO::insertUserWeeklyScoreEntry($resultArray);
        }
    }
}