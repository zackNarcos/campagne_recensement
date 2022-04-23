<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Periode;

class PeriodeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
            $periode = new Periode();
            $periode->setAnnee('1960');
            $periode->setDateDebut(date_create('1960-03-10'));
            $periode->setDateFin(date_create('1960-04-10'));
            $manager->persist($periode);
            $this->addReference("Periode", $periode);

        $manager->flush();
    }
}
