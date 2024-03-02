<?php

namespace App\Controller;

use App\Repository\PaiementRepository;
use App\Entity\Paiement;
use App\Form\PaiementFormeType;
use App\Entity\ReservationHebergement;
use App\Form\ReservationHebergementFormeType;
use App\Entity\ReservationVoyage;
use App\Form\ReservationVoyageFormeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Stripe\Checkout\Session;
use DateTime;
use Dompdf\Dompdf;
use Dompdf\Options;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Charge;
use Stripe\StripeClient;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ReservationController extends AbstractController
{

    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
                //***************************//BACK//******************************//
                       #[Route('/home', name: 'home1')]
                        public function index1(): Response
                        {
                       return $this->render('baseAdmin.html.twig');
                        }
                    
                        #[Route('/listh', name: 'reservation_listh')]
                        public function listh(): Response
                        {
                            $reservations = $this->getDoctrine()->getRepository(ReservationHebergement::class)->findAll();
                    
                            return $this->render('reservation/reservationheb/listh.html.twig', [
                                'reservations' => $reservations,
                            ]);
                        }

                        #[Route('/delete_reservation_heb/{id}', name: 'delete_reservation_heb')]
                        public function deleteReservationheb($id, Request $request): Response
                        {
                            $entityManager = $this->getDoctrine()->getManager();
                            $reservation = $entityManager->getRepository(ReservationHebergement::class)->find($id);

                            if (!$reservation) {
                                throw $this->createNotFoundException('La réservation avec l\'ID '.$id.' n\'existe pas.');
                            }

                            $entityManager->remove($reservation);
                            $entityManager->flush();

                            $this->addFlash('success', 'La réservation a été supprimée avec succès.');

                            return $this->redirectToRoute('reservation_listh');
                        }
                    
                        #[Route('/listv', name: 'reservation_listv')]
                        public function listv(): Response
                        {
                            $reservations = $this->getDoctrine()->getRepository(ReservationVoyage::class)->findAll();
                    
                            return $this->render('reservation/reservationvoy/listv.html.twig', [
                                'reservations' => $reservations,
                            ]);
                        }
                        
                        #[Route('/delete_reservation_voy/{id}', name: 'delete_reservation_voy')]
                        public function deleteReservationvoy($id, Request $request): Response
                        {
                            $entityManager = $this->getDoctrine()->getManager();
                            $reservation = $entityManager->getRepository(ReservationVoyage::class)->find($id);

                            if (!$reservation) {
                                throw $this->createNotFoundException('La réservation avec l\'ID '.$id.' n\'existe pas.');
                            }

                            $entityManager->remove($reservation);
                            $entityManager->flush();

                            $this->addFlash('success', 'La réservation a été supprimée avec succès.');

                            return $this->redirectToRoute('reservation_listv');
                        }

            
                        #[Route('/listp', name: 'list_paiements')]
                        public function listp(): Response
                        {
                            $paiements = $this->getDoctrine()->getRepository(Paiement::class)->findAll();
                            return $this->render('reservation/paiement/listp.html.twig', [
                                'paiements' => $paiements,
                            ]);
                        }

  
                //**************************//FRONT//*****************************//

    #[Route('/reservation', name: 'app_reservation')]
    public function index(): Response
    {
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }

    //-------------------------//partie réservation voyage//-----------------------------//

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

            return $this->redirectToRoute('reservation_voyage_show',['id'=>$reservationVoyage->getId()]);
        }

        return $this->render('reservation/reservationvoy/newv.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reservation-voyage/{id}', name: 'reservation_voyage_show', methods: ['GET'])]
    public function showReservationVoyage(Request $request, ReservationVoyage $reservation): Response
    {
        return $this->render('reservation/reservationvoy/showv.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/reservation-voyage-update/{id}', name: 'modifier_reservation_voyage')]
    public function modifierReservationVoyage(Request $request, ReservationVoyage $reservation): Response
    {
        $form = $this->createForm(ReservationVoyageFormeType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('reservation_voyage_show', ['id' => $reservation->getId()]);
        }

        return $this->render('reservation/reservationvoy/editv.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reservation-voyage/{id}/delete', name: 'delete_reservation_voyage')]
    public function deleteReservationVoyage(Request $request, ReservationVoyage $reservation): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($reservation);
        $entityManager->flush();

        return $this->redirectToRoute('reservation_voyage_new');
    }

     //-------------------------//partie réservation hebergement//-----------------------------//

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

           // Mettre à jour automatiquement le montant de la réservation après la sauvegarde
        //$montantTotal = $reservationHebergement->calculateAmount();
        //$reservationHebergement->getPaiement()->setMontant($montantTotal);
        //$entityManager->flush();
     
             return $this->redirectToRoute('reservation_hebergement_show',['id'=>$reservationHebergement->getId()]);
         }
     
         return $this->render('reservation/reservationheb/newh.html.twig', [
             'form' => $form->createView(),
            
         ]);
     }
     
     
     //affichage detail reservation suivant l'id 
     #[Route('/reservation-hebergement/{id}', name: 'reservation_hebergement_show', methods: ['GET'])]
            public function showReservationHebergement(Request $request, ReservationHebergement $reservation): Response
            {
                return $this->render('reservation/reservationheb/showh.html.twig', [
                    'reservation' => $reservation,
                ]);
            }

    //modifier reservation
    #[Route('/reservation-hebergement-update/{id}', name: 'modifier_reservation_hebergement')]
    public function modifierReservationHebergement(Request $request, ReservationHebergement $reservation): Response
    {
        $form = $this->createForm(ReservationHebergementFormeType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            // Rediriger vers une autre page, par exemple la liste des réservations
            return $this->redirectToRoute('reservation_hebergement_show', ['id' => $reservation->getId()]);
        }

        return $this->render('reservation/reservationheb/edith.html.twig', [
            'form' => $form->createView(),
        ]);
    }
        //effacer reservation
    #[Route('/reservation-hebergement/{id}/delete', name: 'delete_reservation_hebergement')]
    public function deleteReservationHebergement(Request $request, ReservationHebergement $reservation): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($reservation);
        $entityManager->flush();

        // Rediriger vers une autre page après la suppression nouvelle reservation
        return $this->redirectToRoute('reservation_hebergement_new');
    }


         //-------------------------//partie paiement//-----------------------------//

    //modifier paiement
    #[Route('/paiement/{id}/edit', name: 'edit_paiement')]
    public function edit(Request $request, Paiement $paiement): Response
    {
        $form = $this->createForm(PaiementFormeType::class, $paiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('paiement_show', ['id' => $paiement->getId()]);
        }

        return $this->render('reservation/paiement/editp.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //generation pdf facture
    #[Route('/paiement/{id}/generate-pdf', name: 'generate_paiement_pdf')]
    public function generatePdf($id): Response
    {
        // Récupérer le paiement depuis la base de données (remplacez cette ligne par la logique réelle pour récupérer le paiement)
        $paiement = $this->getDoctrine()->getRepository(Paiement::class)->find($id);

        // Vérifier si le paiement existe
        if (!$paiement) {
            throw $this->createNotFoundException('Le paiement avec l\'identifiant '.$id.' n\'existe pas.');
        }

        // Créer une nouvelle instance de Dompdf
        $dompdf = new Dompdf();

        // Chargez le contenu HTML de votre modèle Twig de facture
        $html = $this->renderView('reservation/paiement/pdf.html.twig', [
            'paiement' => $paiement,
        ]);
        // Chargez le contenu HTML dans Dompdf
        $dompdf->loadHtml($html);

        // Rendez le PDF
        $dompdf->render();

        // Retournez le PDF en réponse
        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }

   
       //details du paiement facture
       #[Route('/paiement/{reservationId}/{paiementId}', name: 'paiement_show', methods: ['GET'])]
       public function show(int $reservationId, int $paiementId,PaiementRepository $paiementRepository,MailerInterface $mailer): Response
       {
        $paiement = $paiementRepository->find($paiementId);
        if (!$paiement) {
            throw $this->createNotFoundException('Paiement introuvable');
        }
        $email = (new TemplatedEmail())
        ->from('hawesbiya@example.com')
        ->to('you@example.com')
        ->cc('me@example.com')
        ->subject('Paiement effectué')
        ->htmlTemplate('reservation/paiement/email.html.twig') 
        ->context([
            'paiement' => $paiement,
        ]);
        $mailer->send($email);
        
        
           return $this->render('reservation/paiement/showp.html.twig', [
               'paiement' => $paiement,
           ]);
       }


                          //////////////////STRIPE/////////////////


#[Route('/stripe/{id}', name: 'app_stripe')]
public function index2(ReservationHebergement $reservation): Response
{
    return $this->render('reservation/stripeh.html.twig', [
        'stripe_key' => $_ENV["STRIPE_PUBLIC_KEY"],
        'reservation' => $reservation,
    ]);
}

#[Route('/stripe/create-charge', name: 'app_stripe_charge', methods: ['POST'])]
public function createCharge(Request $request)
{
    \Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET_KEY"]);

    // Récupération des données du formulaire
    $amount = $request->request->get('amount');
    $stripeToken = $request->request->get('stripeToken');
    $reservationId = $request->request->get('reservation_id');

    // Création du paiement Stripe
    \Stripe\Charge::create([
        "amount" => $amount * 100,
        "currency" => "usd",
        "source" => $stripeToken,
        "description" => "Paiement de réservation d'hébergement"
    ]);

    // Enregistrement du paiement dans la base de données
    $entityManager = $this->getDoctrine()->getManager();
    $reservation = $entityManager->getRepository(ReservationHebergement::class)->find($reservationId);

    if (!$reservation) {
        throw $this->createNotFoundException('Réservation introuvable');
    }

    $paiement = new Paiement();
    $paiement->setMontant($amount); // Montant du paiement
    $paiement->setDate(new \DateTime());
    $paiement->setMethode('Stripe'); // Méthode de paiement utilisée
    $reservation->setPaiement($paiement);

    $entityManager->persist($paiement);
    $entityManager->flush();

    // Redirection après paiement réussi
    $this->addFlash('success', 'Payment successfully completed!');
    return $this->redirectToRoute('paiement_show', ['reservationId' => $reservationId, 'paiementId' => $paiement->getId()]);


}


#[Route('/stripe1/{id}', name: 'app_stripe1')]
public function index3(ReservationVoyage $reservation): Response
{
    return $this->render('reservation/stripev.html.twig', [
        'stripe_key' => $_ENV["STRIPE_PUBLIC_KEY"],
        'reservation' => $reservation,
    ]);
}

#[Route('/stripe/create-charge1', name: 'app_stripe_charge1', methods: ['POST'])]
public function createCharge1(Request $request)
{
    \Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET_KEY"]);

    // Récupération des données du formulaire
    $amount = $request->request->get('amount');
    $stripeToken = $request->request->get('stripeToken');
    $reservationId = $request->request->get('reservation_id');

    // Création du paiement Stripe
    \Stripe\Charge::create([
        "amount" => $amount * 100,
        "currency" => "usd",
        "source" => $stripeToken,
        "description" => "Paiement de réservation de voyage"
    ]);

    // Enregistrement du paiement dans la base de données
    $entityManager = $this->getDoctrine()->getManager();
    $reservation = $entityManager->getRepository(ReservationVoyage::class)->find($reservationId);

    if (!$reservation) {
        throw $this->createNotFoundException('Réservation introuvable');
    }

    $paiement = new Paiement();
    $paiement->setMontant($amount); // Montant du paiement
    $paiement->setDate(new \DateTime());
    $paiement->setMethode('Stripe'); // Méthode de paiement utilisée
    $reservation->setPaiement($paiement);

    $entityManager->persist($paiement);
    $entityManager->flush();

    // Redirection après paiement réussi
    $this->addFlash('success', 'Payment successfully completed!');
    return $this->redirectToRoute('paiement_show', ['reservationId' => $reservationId, 'paiementId' => $paiement->getId()]);
}

   
}