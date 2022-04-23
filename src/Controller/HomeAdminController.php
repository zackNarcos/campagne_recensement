<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PeriodeEnCours;
use App\Repository\PeriodeRepository;
use App\Repository\PaysRepository;
use App\Repository\EnregistrementRepository;
use App\Entity\Periode;
use App\Form\PeriodeType;
//use Knp\Component\Pager\PaginatorInterface;
use Twig\Environment;

class HomeAdminController extends AbstractController
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    #[Route('/', name: 'home_admin')]
//    #[Route('/home/admin', name: 'home_admin')]
    public function index(
        PeriodeEnCours         $periodeEnCours,


    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        $periode = $periodeEnCours->getPeriode();
        return $this->render('home_admin/index.html.twig', [
            'controller_name' => 'HomeAdminController',
            'jourRestant' => $periodeEnCours->getJourRestant(),
            'periode' => $periode->getAnnee(),
        ]);
    }

    #[Route('/Pays/{pays}/{periode}', name: 'show_detail_country', methods: ['GET', 'POST'])]
    public function showDetailCountry(
        PeriodeEnCours                  $periodeEnCours,
        Request                         $request,
        PeriodeRepository               $periodeRepo,
        EnregistrementRepository        $enregistrementRepo,
        PaysRepository                  $paysRepo,
        string                          $pays,
        string                          $periode,

    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        $periodes = $periodeRepo->findAll();

        $periodeActive = $periodeRepo->findOneBy(array('annee' => $periode));




        $pays = $paysRepo->findOneBy(array('nom' => $pays));
        $allRegistration = $enregistrementRepo->countAllRegistration($pays->getId(),$periodeActive->getId());
        $studentRegistration = $enregistrementRepo->countStudentRegistration($pays->getId(),$periodeActive->getId());
        $workerRegistration = $enregistrementRepo->countWorkerRegistration($pays->getId(),$periodeActive->getId());
        $chomeurRegistration = $enregistrementRepo->countChomeurRegistration($pays->getId(),$periodeActive->getId());
//        dd($enregistrements);
        return $this->render('show_country/index.html.twig', [
            'jourRestant' => $periodeEnCours->getJourRestant(),
            'periodes' => $periodes,
            'periode' => $periode,
            'pays' => $pays,
            'allRegistration' => $allRegistration[1],
            'studentRegistration' => $studentRegistration[1],
            'workerRegistration' => $workerRegistration[1],
            'chomeurRegistration' => $chomeurRegistration[1],
//            'enregistrements' => $enregistrements,
        ]);
    }


    #[Route('/Pays/{pays}/recensement/{periode}', name: 'show_detail_registration_by_country', methods: ['GET', 'POST'])]
    public function showDetailRegistrationByCountry(
        PeriodeEnCours                  $periodeEnCours,
        PeriodeRepository               $periodeRepo,
        EnregistrementRepository        $enregistrementRepo,
        PaysRepository                  $paysRepo,
        string                          $pays,
        string                          $periode,

    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        $periode = $periodeRepo->findOneBy(array('annee' => $periode));

        $pays = $paysRepo->findOneBy(array('nom' => $pays));
        $enregistrements = $enregistrementRepo->findByCoubtryAndPeriode($pays->getId(),$periode->getId());
//        dd($enregistrements);
        return $this->render('registrement_by_country/index.html.twig', [
            'jourRestant' => $periodeEnCours->getJourRestant(),
            'periode' => $periode,
            'pays' => $pays,
            'enregistrements' => $enregistrements,
        ]);
    }

    #[Route('/validation', name: 'valid_registration', methods: ['GET', 'POST'])]
    public function validRegistration(
        PeriodeEnCours                  $periodeEnCours,
        EnregistrementRepository        $enregistrementRepo,
        Request                         $request,

    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        $offset = max(0, $request->query->getInt('offset', 0));
        $periode = $periodeEnCours->getPeriode();
        $paginator = $enregistrementRepo->getRegistrationPaginator($periode->getId(), $offset);

        return new Response($this->twig->render('show_country/verification.html.twig', [
            'jourRestant' => $periodeEnCours->getJourRestant(),
            'enregistrements' => $paginator,
            'previous' => $offset - EnregistrementRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + EnregistrementRepository::PAGINATOR_PER_PAGE),
        ]));
    }


    #[Route('enregistrement/valider/{id}', name: 'up_registration', methods: ['GET', 'POST'])]
    public function up(int                           $id,
                       ManagerRegistry               $doctrine,
                       EnregistrementRepository      $enregistrementRepository
    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        $entityManager = $doctrine->getManager();

        //recuperation de l'Enregistrement
        $enregistrement = $enregistrementRepository->findOneBy(array('id' => $id));
        $enregistrement->setEtat("VALIDER");
        $entityManager->persist($enregistrement);
        $entityManager->flush();

        return $this->redirectToRoute('valid_registration');
    }

    #[Route('enregistrement/suspendre/{id}', name: 'down_registration', methods: ['GET', 'POST'])]
    public function down(    int                           $id,
                             ManagerRegistry               $doctrine,
                             EnregistrementRepository      $enregistrementRepository
    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        $entityManager = $doctrine->getManager();

        //recuperation de l'Enregistrement
        $enregistrement = $enregistrementRepository->findOneBy(array('id' => $id));
        $entityManager->remove($enregistrement);
        $entityManager->flush();

        return $this->redirectToRoute('valid_registration');
    }

    #[Route('/form/periode', name: 'add_periode', methods: ['GET', 'POST'])]
    public function addPeriode(Request                $request,
                          EntityManagerInterface $em,
                          PeriodeRepository      $periodeRepo,
                          PeriodeEnCours         $periodeEnCours
    ): Response
    {
        $periode = new Periode();
        $form = $this->createForm(PeriodeType::class, $periode);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $verifPeriode = $periodeRepo->findOneBy(array('annee' => $periode->getAnnee()));
            if ($verifPeriode){
                $verifPeriode->setDateDebut($periode->getDateDebut());
                $verifPeriode->setDateFin($periode->getDateFin());
                $em->persist($verifPeriode);
                $em->flush();
                return $this->redirectToRoute('home_admin');
            }

            $em->persist($periode);
            $em->flush();
            return $this->redirectToRoute('home_admin');
        }

        return $this->render('home_admin/addPeriode.html.twig', [
            'my_form' => $form->createView(),'jourRestant' => $periodeEnCours->getJourRestant()
        ]);
    }
}
