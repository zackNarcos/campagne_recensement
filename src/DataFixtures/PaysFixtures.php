<?php

namespace App\DataFixtures;

use App\Entity\Pays;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PaysFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $nom = ["Sénégal", "Guinée Bissau", "Guinée", "Cabo Verde", "Gambie"];


        for ($i = 0; $i <= 4; $i++) {
            $pays = new Pays();
            $image = new Image();
            $pays->setNom($nom[$i]);
            $image->setChemin("" . $nom[$i] . ".png");
            $pays->setImage($image);
            $manager->persist($pays);
            $this->addReference("Pays" . $i, $pays);
        }

        $manager->flush();
    }
}
