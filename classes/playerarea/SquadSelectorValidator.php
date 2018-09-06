<?php

class SquadSelectorValidator
{
    private $validationErrors = array();

    // private $squadCostLimit = 100; - how translate into a member variable

    function calculateCurrentSquadValue($post, $session) {

        $result = false;

        // Extract the cost of the current squad from the session

        // Add the attempted additions to the squad (i.e. the GKs, D, M, or A)

        return $result;


    }

    function validateSquadSelection($post, $session) {

        // Check current squad does not have more than two players from the same team?
    }

    function validateGKSquadForm($post, $session) {

        // Validate that they have selected a maximum of two goalkeepers
        $countSelected = 0;
        foreach($post['PremierPlayer'] as $player) {
            $countSelected += 1;
        }

        if ($countSelected < 2) {
            $validationErrors[] = "You have selected ". $countSelected.
                " goalkeepers. Please select two goalkeepers.";
            return $validationErrors;
        }

        if ($countSelected > 2) {
            $validationErrors[] = "You have selected ". $countSelected.
                " goalkeepers. Please select only two goalkeepers.";
            return $validationErrors;
        }

        // Validate the cost does not exceed £100 million. If it has
        // then throw a validation error. Otherwise add a variable to the session i.e. squadCost
        $currentSquadValue = calculateCurrentSquadValue($post, $session);

        if ($currentSquadValue > (100)) {
            $validationErrors[] = "You have exceeded the maximum squad cost of £100 million. Your squad to date costs".
                $currentSquadValue;
            return $validationErrors;
        }

        // Validate that the current squad does not have more than two players from the same team.
        validateSquadSelection($post, $session);
    }



    /**
    function getUpdatedSquadForSession($post, $currentSquad, $session) {

        // Don't forget the user id

        // Get the players they have selected for the squad so far...

        foreach($post['player'] as $player) {
            $players[] = $player;
        }

        return $players;

    } */
}