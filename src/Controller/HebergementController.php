<?php

namespace App\Controller;

use App\Entity\Hebergement;
use App\Entity\TypeHebergement;
use App\Repository\HebergementRepository;
use App\Repository\TypeHebergementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\HebergementFormeType;
use App\Entity\Avis;
use App\Form\AvisFormeType;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\TypeHebergementFormeType;
use App\Controller\ManagerRegistry;
use App\Repository\UserRepository;


class HebergementController extends AbstractController
{
    //#[Route('/hebergement', name: 'app_hebergement')]
    //public function index(): Response
    //{
    //return $this->render('hebergement/index.html.twig', [
    //'controller_name' => 'HebergementController',
    //]);
    //}

    #[Route('/home', name: 'home')]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }
    #[Route('/choice', name: 'choice')]
    public function choice(): Response
    {
        return $this->render('hebergement/choiceadmin.html.twig');
    }
    #[Route('/liste1', name: 'liste1')]
    public function liste1(HebergementRepository $repo): Response
    {
        $hebergements = $repo->findAll();

        return $this->render('hebergement/index1.html.twig', [
            'hebergements' => $hebergements,
        ]);
    }
    //CRUD hebergement
    #[Route('/liste', name: 'liste')]
    public function liste(Request $request, HebergementRepository $repo, TypeHebergementRepository $typeHebergementRepository): Response
    {
        $type = $request->query->get('type');
        $types = $typeHebergementRepository->findAll();

        if ($type) {
            $hebergements = $repo->findBy(['typeHebergement' => $type]);
        } else {
            $hebergements = $repo->findAll();
        }

        return $this->render('hebergement/index.html.twig', [
            'hebergements' => $hebergements,
            'types' => $types,
            'selectedType' => $type
        ]);
    }
    #[Route('/addhebergement', name: 'addhebergement')]
    public function ajouterHebergement(Request $request): Response
    {
        $hebergement = new Hebergement();
        $form = $this->createForm(HebergementFormeType::class, $hebergement);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($hebergement);
            $entityManager->flush();

            return $this->redirectToRoute('liste1');
        }

        return $this->render('hebergement/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/deletehebergement/{id}', name: 'deletehebergement')]
    public function supprimerHebergement(Request $request, Hebergement $hebergement, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($hebergement);
        $entityManager->flush();

        return $this->redirectToRoute('liste1');
    }


    #[Route('/updatehebergement/{id}', name: 'updatehebergement')]
    public function modifierHebergement(Request $request, Hebergement $hebergement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(HebergementFormeType::class, $hebergement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('liste1');
        }

        return $this->render('hebergement/updatehebergement.html.twig', [
            'hebergement' => $hebergement,
            'form' => $form->createView(),
        ]);
    }

    // end CRUD hebergement
// fonction qui donne detail d'hebergement 
    #[Route('/hebergement/{id}', name: 'hebergement_details')]
    public function show($id): Response
    {
        $hebergement = $this->getDoctrine()->getRepository(Hebergement::class)->find($id);
        if (!$hebergement) {
            throw $this->createNotFoundException('Hébergement non trouvé');
        }
        $avis = $hebergement->getAvis();
        return $this->render('hebergement/details.html.twig', [
            'hebergement' => $hebergement,
            'avis' => $avis,
        ]);
    }
    ///
    ///CRUD typehebergement
    #[Route('/types', name: 'types')]
    public function typesHebergement(): Response
    {
        $typesHebergement = $this->getDoctrine()->getRepository(TypeHebergement::class)->findAll();

        return $this->render('hebergement/type.html.twig', [
            'typesHebergement' => $typesHebergement,
        ]);
    }
    #[Route('/addtype', name: 'addtype')]
    public function ajoutertype(Request $request): Response
    {
        $typesHebergement = new TypeHebergement();
        $form = $this->createForm(TypeHebergementFormeType::class, $typesHebergement);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($typesHebergement);
            $entityManager->flush();

            return $this->redirectToRoute('types');
        }

        return $this->render('hebergement/newtype.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/deletetype/{id}', name: 'deletetype')]
    public function supprimertype(Request $request, TypeHebergement $typesHebergement, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($typesHebergement);
        $entityManager->flush();

        return $this->redirectToRoute('types');
    }


    #[Route('/updatetype/{id}', name: 'updatetype', methods: ['GET', 'POST'])]
    public function modifiertype(Request $request, TypeHebergement $typesHebergement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypeHebergementFormeType::class, $typesHebergement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('types');
        }

        return $this->render('hebergement/updatetype.html.twig', [
            'typeHebergement' => $typesHebergement,
            'form' => $form->createView(),
        ]);
    }
    ////end CRUD type

    ////
/// CRUD avis 
    #[Route('/hebergement/{id}/avis', name: 'hebergement_avis')]
    public function avisHebergement(Hebergement $hebergement): Response
    {
        $avis = $hebergement->getAvis();

        return $this->render('hebergement/avis.html.twig', [
            'hebergement' => $hebergement,
            'avis' => $avis,
        ]);
    }

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route('/avis/ajouter', name: 'hebergement_ajouter_avis')]
    public function ajouterAvis(Request $request, UserRepository $userRepo): Response
    {
        $avis = new Avis();
        $form = $this->createForm(AvisFormeType::class, $avis);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepo->find(1);
            $avis->setOwner($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($avis);
            $entityManager->flush();
            dd('After persist:', $avis);
            return $this->redirectToRoute('hebergement_avis', ['id' => $avis->getHebergement()->getId()]);

        }
        return $this->render('hebergement/addavis.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/avis/{id}/modifier', name: 'avis_modifier')]
    public function modifierAvis(Request $request, Avis $avis): Response
    {
        $form = $this->createForm(AvisFormeType::class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('hebergement_avis', ['id' => $avis->getHebergement()->getId()]);
        }

        return $this->render('hebergement/updateavis.html.twig', [
            'avis' => $avis,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/avis/{id}/supprimer', name: 'avis_supprimer')]
    public function supprimerAvis(Request $request, Avis $avis): Response
    {
        if ($this->isCsrfTokenValid('delete' . $avis->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($avis);
            $entityManager->flush();
        }

        return $this->redirectToRoute('hebergement_avis', ['id' => $avis->getHebergement()->getId()]);
    }
    ////
}
