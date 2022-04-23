<?php

// src/Service/FileUploader.php
namespace App\Service;

use DateTime;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Repository\PeriodeRepository;

class PeriodeEnCours
{
    private $Year;
    private $periode;

    public function __construct(PeriodeRepository      $periodeRepo)
    {
        $this->Year = strftime("%Y");
        if ($periodeRepo->findOneBy(array('annee' => $this->Year))){
            $this->periode = $periodeRepo->findOneBy(array('annee' => $this->Year));
        }else{
            $this->periode = $periodeRepo->findOneBy(array('annee' => '1960'));
        }
    }

    /**
     * @return mixed
     */
    public function getPeriode()
    {
        return $this->periode;
    }


    public function getJourRestant()
    {
//        dd($this);
//        if ($this->periode) {
            $today = new DateTime();
            $jourRestant = $this->periode->getDateFin()->diff($today);
            $days=$jourRestant->days;
//        }
        return $days;
    }
}