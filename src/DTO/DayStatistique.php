<?php

namespace App\DTO;

class DayStatistique
{
    private $soldDay;
    private $comdDay;
    private $comdDayUp;
    private $comdDayDown;

    /**
     * @param $soldDay
     * @param $comdDay
     * @param $comdDayUp
     * @param $comdDayDown
     */
    public function __construct($soldDay, $comdDay, $comdDayUp, $comdDayDown)
    {
        $this->soldDay = $soldDay;
        $this->comdDay = $comdDay;
        $this->comdDayUp = $comdDayUp;
        $this->comdDayDown = $comdDayDown;
    }

    /**
     * @return mixed
     */
    public function getSoldDay()
    {
        return $this->soldDay;
    }

    /**
     * @param mixed $soldDay
     */
    public function setSoldDay($soldDay): void
    {
        $this->soldDay = $soldDay;
    }

    /**
     * @return mixed
     */
    public function getComdDay()
    {
        return $this->comdDay;
    }

    /**
     * @param mixed $comdDay
     */
    public function setComdDay($comdDay): void
    {
        $this->comdDay = $comdDay;
    }

    /**
     * @return mixed
     */
    public function getComdDayUp()
    {
        return $this->comdDayUp;
    }

    /**
     * @param mixed $comdDayUp
     */
    public function setComdDayUp($comdDayUp): void
    {
        $this->comdDayUp = $comdDayUp;
    }

    /**
     * @return mixed
     */
    public function getComdDayDown()
    {
        return $this->comdDayDown;
    }

    /**
     * @param mixed $comdDayDown
     */
    public function setComdDayDown($comdDayDown): void
    {
        $this->comdDayDown = $comdDayDown;
    }


}