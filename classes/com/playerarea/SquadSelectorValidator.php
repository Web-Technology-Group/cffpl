<?php

namespace Com\PlayerArea;

class SquadSelectorValidator
{
    private $MAX_SQUAD_COST = 50.00;
    private $userSquad = array();
    private $premierLeagueTeamsSelectedFrom = array();
    private $currentSquadCost = 0;

    /**
     *  Validate the Goalkeepers squad form. This should ensure that only two goalkeepers have been selected,
     *  that the cost to date of the squad does not exceed £100 million and that no more than two players from the
     *  same premier league team have been selected in the squad.
     * @param $post
     * @param $session
     * @return array
     */
    public function validateGKSquadForm($post) {

        session_start();

        // Validate that they have selected a maximum of two goalkeepers
        $countSelected = 0;
        foreach($post['player'] as $player) {
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

        // Calculate the total cost of the squad to date i.e. just the goalkeepers
        $totalCost = 0;
        foreach($post['player'] as $player) {
            $costString = substr($player, strpos($player, "=") + 1);
            $cost = substr($costString, strpos($costString, "=") + 1);
            $totalCost += $cost;
        }

        // Check the total cost of the squad does not exceed the maximum squad cost
        if ($totalCost > $this->MAX_SQUAD_COST) {
            $validationErrors[] = "The total cost of your squad i.e. "+ $totalCost.
                " exceeds the maximum squad cost of £50 million.";
            return $validationErrors;
        }

        // Add the players selected so far for the squad into the squad array
        foreach($post['player'] as $player) {
            $value = explode('&', $player , 2);
            array_push($this->userSquad, $value[0]);
        }

        // Note, that at this stage no more than two squad members have been selected so cannot
        // have more than two players from the same Premier League team.
        // Add the two teams selected to an array and store in the session for later
        foreach($post['player'] as $player) {

            $team = substr($player, strpos($player, "team") + 5);
            $value = explode('&', $team , 2);
            array_push($this->premierLeagueTeamsSelectedFrom, $value[0]);
        }

        // Store the relevant variables in the session
        $_SESSION['userSquad'] = $this->userSquad;
        $_SESSION['currentSquadCost'] = $totalCost;
        $_SESSION['premierLeagueTeamsSelectedFrom'] = $this->premierLeagueTeamsSelectedFrom;
    }

    /**
     *  Validate the Defenders squad form. This should ensure that only five defenders have been selected,
     *  that the cost to date of the squad does not exceed £100 million and that no more than two players from the
     *  same premier league team have been selected in the squad.
     * @param $post
     * @param $session
     * @return array
     */
    public function validateDefendersSquadForm($post) {

        session_start();

        // Validate that they have selected a maximum of 5 defenders
        $countSelected = 0;
        foreach($post['player'] as $player) {
            $countSelected += 1;
        }

        $validationErrors = array();

        if ($countSelected < 5) {
            $validationErrors[] = "You have selected ". $countSelected.
                " defenders. Please select five defenders.";
            //return $validationErrors;
        }

        if ($countSelected > 5) {
            $validationErrors[] = "You have selected ". $countSelected.
                " defenders. Please select only five defenders.";
            //return $validationErrors;
        }

        // Add the players selected so far for the squad into the squad array
        $this->userSquad = $_SESSION['userSquad'];
        foreach($post['player'] as $player) {
            $value = explode('&', $player , 2);
            array_push($this->userSquad, $value[0]);
        }

        // Validate the cost of the squad to date does not exceed £100 million. This is
        // calculated by extracting the currentSquadCost from the session and adding the total cost of the defenders
        // selected to it. If the cost exceeds the maximum then throw a validation error.

        // Extract the current squad cost from session
        $currentSquadCost = $_SESSION['currentSquadCost'];

        $totalCost = 0;
        foreach($post['player'] as $player) {
            $costString = substr($player, strpos($player, "=") + 1);
            $cost = substr($costString, strpos($costString, "=") + 1);
            $totalCost += $cost;
        }

        if (($totalCost + $currentSquadCost) > $this->MAX_SQUAD_COST) {
            $validationErrors[] = "The total cost of your squad i.e. "+ ($totalCost + $currentSquadCost).
                " exceeds the maximum squad cost of £50 million.";
            //return $validationErrors;
        } else {
            $this->currentSquadCost = $totalCost + $currentSquadCost;
        }

        // Validate that the current squad does not have more than two players from the same team.
        // Extract the premierLeagueTeamsSelectedFrom array from the session and add the teams that
        // relate to the defenders selected
        $this->premierLeagueTeamsSelectedFrom = $_SESSION['premierLeagueTeamsSelectedFrom'];

        // Add the players selected to the premierLeagueTeamsSelectedFrom array
        foreach($post['player'] as $player) {

            $team = substr($player, strpos($player, "team") + 5);
            $value = explode('&', $team , 2);
            array_push($this->premierLeagueTeamsSelectedFrom, $value[0]);
        }

        // Get the teams that have just been added to the array and count to see if any number is greater than 2
        $premierLeagueTeamsSelectedFromArray = array_count_values($this->premierLeagueTeamsSelectedFrom);

        foreach ($premierLeagueTeamsSelectedFromArray as $key => $numberOfTimesTeamSelected) {
            if ($numberOfTimesTeamSelected > 2) {
                $validationErrors[] =
                    "The total number of players from  ". array_search($numberOfTimesTeamSelected, $premierLeagueTeamsSelectedFromArray).
                    " exceeds the maximum number of two. Only two players from the same Premier League team can be ".
                     " selected for the squad.";
                //return $validationErrors;
            }
        }

        //die("validationErrors is greater than zero i.e. ".count($validationErrors));
        if (count($validationErrors) > 0) {
            // Unset the values and revert to the previous ones
            // die("2validationErrors is greater than zero i.e. ".count($validationErrors[]));
            return $validationErrors;
        } else {
            // Store the relevant variables in the session
            $_SESSION['userSquad'] = $this->userSquad;
            $_SESSION['currentSquadCost'] = $totalCost;
            $_SESSION['premierLeagueTeamsSelectedFrom'] = $this->premierLeagueTeamsSelectedFrom;
        }


    }

    /**
     *  Validate the Midfielders squad form. This should ensure that only five midfielders have been selected,
     *  that the cost to date of the squad does not exceed £100 million and that no more than two players from the
     *  same premier league team have been selected in the squad.
     * @param $post
     * @param $session
     * @return array
     */
    public function validateMidfieldersSquadForm($post) {

        session_start();

        $validationErrors = array();

        // Validate that they have selected a maximum of 5 midfielders
        $countSelected = 0;
        foreach($post['player'] as $player) {
            $countSelected += 1;
        }

        if ($countSelected < 5) {
            $validationErrors[] = "You have selected ". $countSelected.
                " midfielders. Please select five midfielders.";
            //return $validationErrors;
        }

        if ($countSelected > 5) {
            $validationErrors[] = "You have selected ". $countSelected.
                " midfielders. Please select only five midfielders.";
            //return $validationErrors;
        }

        // Add the players selected so far for the squad into the squad array
        $this->userSquad = $_SESSION['userSquad'];
        foreach($post['player'] as $player) {
            $value = explode('&', $player , 2);
            array_push($this->userSquad, $value[0]);
        }

        // Validate the cost of the squad to date does not exceed £100 million. This is
        // calculated by extracting the currentSquadCost from the session and adding the total cost of the midfielders
        // selected to it. If the cost exceeds the maximum then throw a validation error.

        // Extract the current squad cost from session
        $currentSquadCost = $_SESSION['currentSquadCost'];

        $totalCost = 0;
        foreach($post['player'] as $player) {
            $costString = substr($player, strpos($player, "=") + 1);
            $cost = substr($costString, strpos($costString, "=") + 1);
            $totalCost += $cost;
        }

        if (($totalCost + $currentSquadCost) > $this->MAX_SQUAD_COST) {
            $validationErrors[] = "The total cost of your squad i.e. "+ ($totalCost + $currentSquadCost).
                " exceeds the maximum squad cost of £50 million.";
            //return $validationErrors;
        } else {
            $this->currentSquadCost = $totalCost + $currentSquadCost;
        }

        // Validate that the current squad does not have more than two players from the same team.
        // Extract the premierLeagueTeamsSelectedFrom array from the session and add the teams that
        // relate to the midfielders selected
        $this->premierLeagueTeamsSelectedFrom = $_SESSION['premierLeagueTeamsSelectedFrom'];

        foreach($post['player'] as $player) {

            $team = substr($player, strpos($player, "team") + 5);
            $value = explode('&', $team , 2);
            array_push($this->premierLeagueTeamsSelectedFrom, $value[0]);
        }

        // Get the teams that have just been added to the array and count to see if any number is greater than 2
        $premierLeagueTeamsSelectedFromArray = array_count_values($this->premierLeagueTeamsSelectedFrom);

        foreach ($premierLeagueTeamsSelectedFromArray as $key => $numberOfTimesTeamSelected) {
            if ($numberOfTimesTeamSelected > 2) {
                $validationErrors[] =
                    "The total number of players from  ". array_search($numberOfTimesTeamSelected, $premierLeagueTeamsSelectedFromArray).
                    " exceeds the maximum number of two. Only two players from the same Premier League team can be ".
                    " selected for the squad.";
                //return $validationErrors;
            }
        }

        //die("validationErrors is greater than zero i.e. ".count($validationErrors));
        if (count($validationErrors) > 0) {
            // Unset the values and revert to the previous ones
            // die("2validationErrors is greater than zero i.e. ".count($validationErrors[]));
            return $validationErrors;
        } else {
            // Store the relevant variables in the session
            $_SESSION['userSquad'] = $this->userSquad;
            $_SESSION['currentSquadCost'] = $totalCost;
            $_SESSION['premierLeagueTeamsSelectedFrom'] = $this->premierLeagueTeamsSelectedFrom;
        }
    }

    /**
     *  Validate the Attackers squad form. This should ensure that only five attackers have been selected,
     *  that the cost to date of the squad does not exceed £100 million and that no more than two players from the
     *  same premier league team have been selected in the squad.
     * @param $post
     * @param $session
     * @return array
     */
    public function validateAttackersSquadForm($post) {

        session_start();

        $validationErrors = array();

        // Validate that they have selected a maximum of 3 attackers
        $countSelected = 0;
        foreach($post['player'] as $player) {
            $countSelected += 1;
        }

        if ($countSelected < 3) {
            $validationErrors[] = "You have selected ". $countSelected.
                " attackers. Please select three attackers.";
            //return $validationErrors;
        }

        if ($countSelected > 3) {
            $validationErrors[] = "You have selected ". $countSelected.
                " attackers. Please select only three attackers.";
            //return $validationErrors;
        }

        // Add the players selected so far for the squad into the squad array
        $this->userSquad = $_SESSION['userSquad'];
        foreach($post['player'] as $player) {
            $value = explode('&', $player , 2);
            array_push($this->userSquad, $value[0]);
        }

        // Validate the cost of the squad to date does not exceed £100 million. This is
        // calculated by extracting the currentSquadCost from the session and adding the total cost of the attackers
        // selected to it. If the cost exceeds the maximum then throw a validation error.

        // Extract the current squad cost from session
        $currentSquadCost = $_SESSION['currentSquadCost'];

        $totalCost = 0;
        foreach($post['player'] as $player) {
            $costString = substr($player, strpos($player, "=") + 1);
            $cost = substr($costString, strpos($costString, "=") + 1);
            $totalCost += $cost;
        }

        if (($totalCost + $currentSquadCost) > $this->MAX_SQUAD_COST) {
            $validationErrors[] = "The total cost of your squad i.e. "+ ($totalCost + $currentSquadCost).
                " exceeds the maximum squad cost of £50 million.";
            //return $validationErrors;
        } else {
            $this->currentSquadCost = $totalCost + $currentSquadCost;
        }

        // Validate that the current squad does not have more than two players from the same team.
        // Extract the premierLeagueTeamsSelectedFrom array from the session and add the teams that
        // relate to the attackers selected
        $this->premierLeagueTeamsSelectedFrom = $_SESSION['premierLeagueTeamsSelectedFrom'];

        foreach($post['player'] as $player) {

            $team = substr($player, strpos($player, "team") + 5);
            $value = explode('&', $team , 2);
            array_push($this->premierLeagueTeamsSelectedFrom, $value[0]);
        }

        // Get the teams that have just been added to the array and count to see if any number is greater than 2
        $premierLeagueTeamsSelectedFromArray = array_count_values($this->premierLeagueTeamsSelectedFrom);

        foreach ($premierLeagueTeamsSelectedFromArray as $key => $numberOfTimesTeamSelected) {
            if ($numberOfTimesTeamSelected > 2) {
                $validationErrors[] =
                    "The total number of players from  ". array_search($numberOfTimesTeamSelected, $premierLeagueTeamsSelectedFromArray).
                    " exceeds the maximum number of two. Only two players from the same Premier League team can be ".
                    " selected for the squad.";
                //return $validationErrors;
            }
        }

        //die("validationErrors is greater than zero i.e. ".count($validationErrors));
        if (count($validationErrors) > 0) {
            // Unset the values and revert to the previous ones
            // die("2validationErrors is greater than zero i.e. ".count($validationErrors[]));
            return $validationErrors;
        } else {
            // Store the relevant variables in the session
            $_SESSION['userSquad'] = $this->userSquad;
            $_SESSION['currentSquadCost'] = $totalCost;
            $_SESSION['premierLeagueTeamsSelectedFrom'] = $this->premierLeagueTeamsSelectedFrom;
        }
    }



}