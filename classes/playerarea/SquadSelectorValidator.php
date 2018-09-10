<?php

require_once('PremierTeamLoader.php');

class SquadSelectorValidator
{
    private $validationErrors = array();
    private$MAX_SQUAD_COST = 100.00;
    //private$PREMIER_TEAMS = '';
    //private$PREMIER_PLAYERS = '';
    private$userSquad = array();
    private$premierLeagueTeamsSelectedFrom = array();
    // private$currentUserSquadCost = array();

    function __construct()
    {
        //$this->PREMIER_TEAMS = PremierTeamLoader::getPremierLeagueTeams();
        // $this->PREMIER_PLAYERS = PremierPlayerLoader::getPremierPlayers();
    }

    function getUserSquad() {
        return $this->userSquad;
    }

    function getPremierLeagueTeamsSelectedFrom() {
        return $this->premierLeagueTeamsSelectedFrom;
    }

    /**
     *  Validate the Goalkeepers squad form. This should ensure that only two goalkeepers have been selected,
     *  that the cost to date of the squad does not exceed £100 million and that no more than two players from the
     *  same premier league team have been selected in the squad.
     * @param $post
     * @param $session
     * @return array
     */
    function validateGKSquadForm($post, $session) {

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

        $totalCost = 0;
        foreach($post['player'] as $player) {
            $cost = substr($player, strpos($player, "=") + 1);
            $totalCost += $cost;

        }

        if ($totalCost > $this->MAX_SQUAD_COST) {
            $validationErrors[] = "The total cost of your squad i.e. "+ $totalCost.
                " exceeds the maximum squad cost of £100 million.";
            return $validationErrors;
        }

        // Add the players selected so far for the squad into the squad array
        // $userSquad = array();
        foreach($post['player'] as $player) {
            array_push($this->userSquad, $player);
        }

        // Note, that at this stage no more than two squad members have been selected so cannot
        // have more than two players from the same Premier League team.
        // Add the two teams selected to an array and store in the session for later
        foreach($post['player'] as $player) {

            $team = substr($player, strpos($player, "team") + 5);
            $value = explode('&', $team , 2);
            array_push($this->premierLeagueTeamsSelectedFrom, $value[0]);
        }

        // Add the relevant variables to the session
        //$session['userSquad'] = $userSquad;
        // $session['currentSquadCost'] = $totalCost;
        // $session['premierLeagueTeamsSelectedFrom'] = $this->premierLeagueTeamsSelectedFrom;
        //die("2 premierLeagueTeamsSelectedFrom for session: ". var_dump($premierLeagueTeamsSelectedFrom).
       //  " and for the session=". var_dump($session));
        //echo "3=";
        //die(var_dump($session));

    }

    /**
     *  Validate the Defenders squad form. This should ensure that only five defenders have been selected,
     *  that the cost to date of the squad does not exceed £100 million and that no more than two players from the
     *  same premier league team have been selected in the squad.
     * @param $post
     * @param $session
     * @return array
     */
    function validateDefendersSquadForm($post, $session) {

        // echo "In validateDefendersSquadForm...";
        // die(var_dump($session));

        // Validate that they have selected a maximum of 5 defenders
        $countSelected = 0;
        foreach($post['player'] as $player) {
            $countSelected += 1;
        }

        if ($countSelected < 5) {
            $validationErrors[] = "You have selected ". $countSelected.
                " defenders. Please select five defenders.";
            return $validationErrors;
        }

        if ($countSelected > 5) {
            $validationErrors[] = "You have selected ". $countSelected.
                " defenders. Please select only five defenders.";
            return $validationErrors;
        }

        // Add the players selected so far for the squad into the squad array
        $this->userSquad = $session['userSquad'];
        foreach($post['player'] as $player) {
            array_push($this->userSquad, $player);
        }

        // Validate the cost of the squad to date does not exceed £100 million. This is
        // calculated by extracting the currentSquadCost from the session and adding the total cost of the defenders
        // selected to it. If the cost exceeds the maximum then throw a validation error.

        // Extract the current squad cost from session
        $currentSquadCost = $session['currentSquadCost'];

        $totalCost = 0;
        foreach($post['player'] as $player) {
            $cost = substr($player, strpos($player, "=") + 1);
            $totalCost += $cost;
        }

        if (($totalCost + $currentSquadCost) > $this->MAX_SQUAD_COST) {
            $validationErrors[] = "The total cost of your squad i.e. "+ $totalCost.
                " exceeds the maximum squad cost of £100 million.";
            return $validationErrors;
        }

        // Validate that the current squad does not have more than two players from the same team.
        // Extract the premierLeagueTeamsSelectedFrom array from the session and add the teams that
        // relate to the defenders selected
        $this->premierLeagueTeamsSelectedFrom = $session['premierLeagueTeamsSelectedFrom'];


        // die("premierLeagueTeamsSelectedFrom: ". var_dump($premierLeagueTeamsSelectedFrom));

        foreach($post['player'] as $player) {

            $team = substr($player, strpos($player, "team") + 5);
            $value = explode('&', $team , 2);
            array_push($this->premierLeagueTeamsSelectedFrom, $value[0]);
        }

        // Get the teams that have just been added to the array and count to see if any number is greater than 2
        $premierLeagueTeamsSelectedFromArray = array_count_values($this->premierLeagueTeamsSelectedFrom);

        foreach ($premierLeagueTeamsSelectedFromArray as $key => $numberOfTimesTeamSelected) {
            if ($numberOfTimesTeamSelected > 2) {
                $validationErrors[] = "The total number of players from  "+ $numberOfTimesTeamSelected.
                    " exceeds the maximum number of two. Only two players from the same Premier League can be ".
                " selected for the squad.";
                return $validationErrors;
            }
        }

        // Add the relevant variables to the session
        //$session['userSquad'] = $userSquad;
        //$session['currentSquadCost'] = $totalCost;
        //$session['premierLeagueTeamsSelectedFrom'] = $premierLeagueTeamsSelectedFrom;
    }

    /**
     *  Validate the Midfielders squad form. This should ensure that only five midfielders have been selected,
     *  that the cost to date of the squad does not exceed £100 million and that no more than two players from the
     *  same premier league team have been selected in the squad.
     * @param $post
     * @param $session
     * @return array
     */
    function validateMidfieldersSquadForm($post, $session) {

        // Validate that they have selected a maximum of 5 midfielders
        $countSelected = 0;
        foreach($post['player'] as $player) {
            $countSelected += 1;
        }

        if ($countSelected < 5) {
            $validationErrors[] = "You have selected ". $countSelected.
                " midfielders. Please select five midfielders.";
            return $validationErrors;
        }

        if ($countSelected > 5) {
            $validationErrors[] = "You have selected ". $countSelected.
                " midfielders. Please select only five midfielders.";
            return $validationErrors;
        }

        // Add the players selected so far for the squad into the squad array
        $userSquad = $session['userSquad'];
        foreach($post['player'] as $player) {
            array_push($userSquad, $player);
        }

        // Validate the cost of the squad to date does not exceed £100 million. This is
        // calculated by extracting the currentSquadCost from the session and adding the total cost of the midfielders
        // selected to it. If the cost exceeds the maximum then throw a validation error.

        // Extract the current squad cost from session
        $currentSquadCost = $session['currentSquadCost'];

        $totalCost = 0;
        foreach($post['player'] as $player) {

            $team = substr($player, strpos($player, "team") + 5);
            $value = explode('&', $team , 2);
            array_push($premierLeagueTeamsSelectedFrom, $value[0]);
        }

        if (($totalCost + $currentSquadCost) > $this->MAX_SQUAD_COST) {
            $validationErrors[] = "The total cost of your squad i.e. "+ $totalCost.
                " exceeds the maximum squad cost of £100 million.";
            return $validationErrors;
        }

        // Validate that the current squad does not have more than two players from the same team.
        // Extract the premierLeagueTeamsSelectedFrom array from the session and add the teams that
        // relate to the midfielders selected
        $premierLeagueTeamsSelectedFrom = $session['premierLeagueTeamsSelectedFrom'];

        foreach($post['player'] as $player) {

            $team = substr($player, strpos($player, "team") + 5);
            $value = explode('&', $team , 2);
            array_push($premierLeagueTeamsSelectedFrom, $value[0]);
        }

        // Get the teams that have just been added to the array and count to see if any number is greater than 2
        $premierLeagueTeamsSelectedFromArray = array_count_values($premierLeagueTeamsSelectedFrom);

        foreach ($premierLeagueTeamsSelectedFromArray as $key => $numberOfTimesTeamSelected) {
            if ($numberOfTimesTeamSelected > 2) {
                $validationErrors[] = "The total number of players from  "+ $key.
                    " exceeds the maximum number of two. Only two players from the same Premier League can be ".
                    " selected for the squad.";
                return $validationErrors;
            }
        }

        // Add the relevant variables to the session
        $session['userSquad'] = $userSquad;
        $session['currentSquadCost'] = $totalCost;
        $session['premierLeagueTeamsSelectedFrom'] = $premierLeagueTeamsSelectedFrom;
    }

    /**
     *  Validate the Attackers squad form. This should ensure that only five attackers have been selected,
     *  that the cost to date of the squad does not exceed £100 million and that no more than two players from the
     *  same premier league team have been selected in the squad.
     * @param $post
     * @param $session
     * @return array
     */
    function validateAttackersSquadForm($post, $session) {

        // Validate that they have selected a maximum of 3 attackers
        $countSelected = 0;
        foreach($post['player'] as $player) {
            $countSelected += 1;
        }

        if ($countSelected < 3) {
            $validationErrors[] = "You have selected ". $countSelected.
                " attackers. Please select five attackers.";
            return $validationErrors;
        }

        if ($countSelected > 3) {
            $validationErrors[] = "You have selected ". $countSelected.
                " attackers. Please select only five attackers.";
            return $validationErrors;
        }

        // Add the players selected so far for the squad into the squad array
        $userSquad = $session['userSquad'];
        foreach($post['player'] as $player) {
            array_push($userSquad, $player);
        }

        // Validate the cost of the squad to date does not exceed £100 million. This is
        // calculated by extracting the currentSquadCost from the session and adding the total cost of the attackers
        // selected to it. If the cost exceeds the maximum then throw a validation error.

        // Extract the current squad cost from session
        $currentSquadCost = $session['currentSquadCost'];

        $totalCost = 0;
        foreach($post['player'] as $player) {
            $cost = substr($player, strpos($player, "=") + 1);
            $totalCost += $cost;

        }

        if (($totalCost + $currentSquadCost) > $this->MAX_SQUAD_COST) {
            $validationErrors[] = "The total cost of your squad i.e. "+ $totalCost.
                " exceeds the maximum squad cost of £100 million.";
            return $validationErrors;
        }

        // Validate that the current squad does not have more than two players from the same team.
        // Extract the premierLeagueTeamsSelectedFrom array from the session and add the teams that
        // relate to the attackers selected
        $premierLeagueTeamsSelectedFrom = $session['premierLeagueTeamsSelectedFrom'];

        foreach($post['player'] as $player) {

            $team = substr($player, strpos($player, "team") + 5);
            $value = explode('&', $team , 2);
            array_push($premierLeagueTeamsSelectedFrom, $value[0]);
        }

        // Get the teams that have just been added to the array and count to see if any number is greater than 2
        $premierLeagueTeamsSelectedFromArray = array_count_values($premierLeagueTeamsSelectedFrom);

        foreach ($premierLeagueTeamsSelectedFromArray as $key => $numberOfTimesTeamSelected) {
            if ($numberOfTimesTeamSelected > 2) {
                $validationErrors[] = "The total number of players from  "+ $key.
                    " exceeds the maximum number of two. Only two players from the same Premier League can be ".
                    " selected for the squad.";
                return $validationErrors;
            }
        }

        // Add the relevant variables to the session
        $session['userSquad'] = $userSquad;
        $session['currentSquadCost'] = $totalCost;
        $session['premierLeagueTeamsSelectedFrom'] = $premierLeagueTeamsSelectedFrom;
        $session['squadSubmit'] = "squadSubmit";
    }



}