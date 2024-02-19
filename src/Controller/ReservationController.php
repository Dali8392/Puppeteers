<?php

namespace App\Controller;

use App\Entity\Paiement;
<<<<<<< HEAD
use App\Form\PaiementFormeType;
use App\Entity\ReservationHebergement;
use App\Form\ReservationHebergementFormeType;
use App\Entity\ReservationVoyage;
use App\Form\ReservationVoyageFormeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

=======
use App\Entity\ReservationHebergement;
use App\Entity\ReservationVoyage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
>>>>>>> 42a7833db9154d776e467dfb64612089dc7ce596

class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation')]
    public function index(): Response
    {
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }
<<<<<<< HEAD

    //-------------------------//partie réservation voyage-----------------------------//

    #[Route('/reservation-voyage/new', name: 'reservation_voyage_new')]
    public function newv(Request $request): Response
    {
        $reservationVoyage = new ReservationVoyage();
        $form = $this->createForm(ReservationVoyageFormeType::class, $reservationVoyage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($reservationVoyage);
            $entityManager->flush();

            return $this->redirectToRoute('paiement_new');
        }

        return $this->render('reservation/reservationvoy/newv.html.twig', [
            'form' => $form->createView(),
        ]);
    }

     //-------------------------//partie réservation hebergement-----------------------------//

     #[Route('/reservation-hebergement/new', name: 'reservation_hebergement_new')]
     public function newh(Request $request): Response
     {
         $reservationHebergement = new ReservationHebergement();
         $form = $this->createForm(ReservationHebergementFormeType::class, $reservationHebergement);
         $form->handleRequest($request);
     
         if ($form->isSubmitted() && $form->isValid()) {
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($reservationHebergement);
             $entityManager->flush();
     
             return $this->redirectToRoute('reservation_hebergement_show',['id'=>$reservationHebergement->getId()]);
         }
     
         return $this->render('reservation/reservationheb/newh.html.twig', [
             'form' => $form->createView(),
            
         ]);
     }
     
     
     #[Route('/reservation-hebergement/{id}', name: 'reservation_hebergement_show', methods: ['GET'])]
            public function showReservationHebergement(Request $request, ReservationHebergement $reservation): Response
            {
                return $this->render('reservation/reservationheb/showh.html.twig', [
                    'reservation' => $reservation,
                ]);
            }

    #[Route('/reservation-hebergement-update/{id}', name: 'modifier_reservation_hebergement')]
    public function modifierReservationHebergement(Request $request, ReservationHebergement $reservation): Response
    {
        $form = $this->createForm(ReservationHebergementFormeType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            // Rediriger vers une autre page, par exemple la liste des réservations
            return $this->redirectToRoute('reservation_hebergement_show');
        }

        return $this->render('reservation/reservationheb/edith.html.twig', [
            'form' => $form->createView(),
        ]);
    }


         //-------------------------//partie paiement-----------------------------//

    #[Route('/paiement/new', name: 'paiement_new')]
    public function new(Request $request): Response
    {
        $paiement = new Paiement();
        $form = $this->createForm(PaiementFormeType::class, $paiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($paiement);
            $entityManager->flush();

            return $this->redirectToRoute('paiement_show', ['id' => $paiement->getId()]);

        }

        return $this->render('reservation/paiement/newp.html.twig', [
            'form' => $form->createView(),
        ]);
    }
          //details du paiement facture
    #[Route('/paiement/{id}', name: 'paiement_show', methods: ['GET'])]
    public function show(Paiement $paiement): Response
    {
        return $this->render('reservation/paiement/showp.html.twig', [
            'paiement' => $paiement,
        ]);
    }
    

=======
>>>>>>> 42a7833db9154d776e467dfb64612089dc7ce596
}