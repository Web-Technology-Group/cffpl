<?php

class PremierPlayer
{
    // Setup the default object properties
    private$id = 0;
    private$name = '';
    private$team = '';
    public$cost = 0.00;
    private$position = '';
    private$points = 0;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param string $team
     */
    public function setTeam($team)
    {
        $this->team = $team;
    }

    /**
     * @return int
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param int $cost
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param string $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return int
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @param int $points
     */
    public function setPoints($points)
    {
        $this->points = $points;
    }
}