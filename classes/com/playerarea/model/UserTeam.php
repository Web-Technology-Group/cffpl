<?php

class UserTeam
{
    // Setup the default object properties
    private$team = array();

    /**
     * @param PremierPlayer $premierPlayer
     */
    public function addPlayer(PremierPlayer $premierPlayer) {
        $this->team = $premierPlayer;
    }

    /**
     * @param PremierPlayer $premierPlayer
     */
    public function removePlayer(PremierPlayer $premierPlayer) {
        $id = $premierPlayer->getId();

        unset($this->team[$id]);
    }

    /**
     * @return array
     */
    public function getTeam()
    {
        return $this->team;
    }
}