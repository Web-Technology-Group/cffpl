<?php

namespace Com\PlayerArea;


class TeamSelectorValidator
{
    private $userTeam = array();

    /**
     * @param $post
     */
    public function validateGKTeamForm($post) {

        session_start();

        // Validate that they have selected a maximum of one goalkeeper
        $countSelected = 0;
        foreach($post['player'] as $player) {
            $countSelected += 1;
        }

        if ($countSelected < 1) {
            $validationErrors[] = "You have selected ". $countSelected.
                " goalkeepers. Please select one goalkeeper.";
            return $validationErrors;
        }

        if ($countSelected > 1) {
            $validationErrors[] = "You have selected ". $countSelected.
                " goalkeepers. Please select only one goalkeeper.";
            return $validationErrors;
        }
        // Add the players selected so far from the squad into the team array
        foreach($post['player'] as $player) {
            $value = explode('&', $player , 2);
            array_push($this->userTeam, $value[0]);
        }

        // Store the relevant variables in the session
        $_SESSION['userTeam'] = $this->userTeam;
    }

    /**
     * @param $post
     */
    public function validateDefendersTeamForm($post) {

        session_start();

        // Validate that they have selected a maximum of four defenders
        $countSelected = 0;
        foreach($post['player'] as $player) {
            $countSelected += 1;
        }

        if ($countSelected < 4) {
            $validationErrors[] = "You have selected ". $countSelected.
                " defenders. Please select four defenders.";
            return $validationErrors;
        }

        if ($countSelected > 4) {
            $validationErrors[] = "You have selected ". $countSelected.
                " defenders. Please select only four defenders.";
            return $validationErrors;
        }
        // Add the players selected so far from the squad into the team array
        $this->userTeam = $_SESSION['userTeam'];
        foreach($post['player'] as $player) {
            $value = explode('&', $player , 2);
            array_push($this->userTeam, $value[0]);
        }

        // Store the relevant variables in the session
        $_SESSION['userTeam'] = $this->userTeam;
    }

    /**
     * @param $post
     */
    public function validateMidfieldersTeamForm($post) {

        session_start();

        // Validate that they have selected a maximum of four midfielders
        $countSelected = 0;
        foreach($post['player'] as $player) {
            $countSelected += 1;
        }

        if ($countSelected < 4) {
            $validationErrors[] = "You have selected ". $countSelected.
                " midfielders. Please select four midfielders.";
            return $validationErrors;
        }

        if ($countSelected > 4) {
            $validationErrors[] = "You have selected ". $countSelected.
                " midfielders. Please select only four midfielders.";
            return $validationErrors;
        }
        // Add the players selected so far from the squad into the team array
        $this->userTeam = $_SESSION['userTeam'];
        foreach($post['player'] as $player) {
            $value = explode('&', $player , 2);
            array_push($this->userTeam, $value[0]);
        }

        // Store the relevant variables in the session
        $_SESSION['userTeam'] = $this->userTeam;
    }

    /**
     * @param $post
     */
    public function validateAttackersTeamForm($post) {

        session_start();

        // Validate that they have selected a maximum of four attackers
        $countSelected = 0;
        foreach($post['player'] as $player) {
            $countSelected += 1;
        }

        if ($countSelected < 2) {
            $validationErrors[] = "You have selected ". $countSelected.
                " attackers. Please select two attackers.";
            return $validationErrors;
        }

        if ($countSelected > 2) {
            $validationErrors[] = "You have selected ". $countSelected.
                " attackers. Please select only two attackers.";
            return $validationErrors;
        }
        // Add the players selected so far from the squad into the team array
        $this->userTeam = $_SESSION['userTeam'];
        foreach($post['player'] as $player) {
            $value = explode('&', $player , 2);
            array_push($this->userTeam, $value[0]);
        }

        // Store the relevant variables in the session
        $_SESSION['userTeam'] = $this->userTeam;
    }

}