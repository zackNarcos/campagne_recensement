<?php

namespace App\Controller;

use App\Service\FileUploader;
use App\Service\PeriodeEnCours;
use App\Entity\Enregistrement;
use App\Form\EnregistrementType;
use App\Repository\PeriodeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormRegistrationController extends AbstractController
{
    #[Route('/form/registration', name: 'form_registration', methods: ['GET', 'POST'])]
    public function index(Request                $request,
                          EntityManagerInterface $em,
                          PeriodeRepository      $periodeRepo,
                          FileUploader           $fileUploader,
                          PeriodeEnCours         $periodeEnCours
    ): Response
    {
        $registration = new Enregistrement();
        $form = $this->createForm(EnregistrementType::class, $registration);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $Year = strftime("%Y");
            $periodeEnCours = $periodeRepo->findOneBy(array('annee' => $Year));
            $registration->setEtat("ATTENTE");
            $registration->setPeriode($periodeEnCours);
            $registrationFile = $form->get('image')->get('chemin')->getData();
            if ($registrationFile) {
                $registrationFileName = $fileUploader->upload($registrationFile);
                $registration->getImage()->setChemin($registrationFileName);
            }

            $em->persist($registration);
            $em->flush();
            return $this->redirectToRoute('form_registration');
        }

        return $this->render('form_registration/index.html.twig', [
            'my_form' => $form->createView(),'jourRestant' => $periodeEnCours->getJourRestant()
        ]);
    }
}
