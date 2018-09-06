<?php

require_once('PremierPlayer.php');

class UserSquad
{
    // Setup the default object properties
    private $squad = array();

    /**
     * @param PremierPlayer $premierPlayer
     */
    public function addPlayer(PremierPlayer $premierPlayer) {
        $this->squad = $premierPlayer;
    }

    /**
     * @param PremierPlayer $premierPlayer
     */
    public function removePlayer(PremierPlayer $premierPlayer) {
        $id = $premierPlayer->getId();

        unset($this->squad[$id]);
    }

    /**
     * @return array
     */
    public function getSquad()
    {
        return $this->squad;
    }

    public function getTotalSquadCost() {
        //foreach ($associativeArrayAuthors as $key => $valAuthor)
        foreach($this->squad as $premierPlayer) {
            $vars = get_object_vars($premierPlayer);
        }
    }
}