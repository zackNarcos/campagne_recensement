<?php

namespace App\DTO;

class MonthStatistique
{
    private $mois;
    private $donnee;

    /**
     * @param $mois
     * @param $donnee
     */
    public function __construct($mois, $donnee)
    {
        $this->mois = $mois;
        $this->donnee = $donnee;
    }

    /**
     * @return mixed
     */
    public function getMois()
    {
        return $this->mois;
    }

    /**
     * @return mixed
     */
    public function getDonnee()
    {
        return $this->donnee;
    }


}