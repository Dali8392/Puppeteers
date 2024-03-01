<?php

namespace App\Controller;

use App\Entity\Hebergement;
use App\Entity\TypeHebergement;
use App\Repository\HebergementRepository;
use App\Repository\ReservationHebergementRepository;
use App\Repository\TypeHebergementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Form\HebergementFormeType;
use App\Entity\Avis;
use App\Form\AvisFormeType;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\TypeHebergementFormeType;
use App\Controller\ManagerRegistry;
use App\Repository\UserRepository;
use App\Repository\AvisRepository;
use App\Controller\AvisFormType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Knp\Component\Pager\PaginatorInterface;


class HebergementController extends AbstractController
{
    //#[Route('/hebergement', name: 'app_hebergement')]
    //public function index(): Response
    //{
    //return $this->render('hebergement/index.html.twig', [
    //'controller_name' => 'HebergementController',
    //]);
    //}
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        $this->session->set('id', "aaaaa");
        $this->session->set('name', "mjlkjlklhlkh");
    }

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
        $nombreHebergements = count($hebergements);


        return $this->render('hebergement/index1.html.twig', [
            'hebergements' => $hebergements,
            'nombre_hebergements' => $nombreHebergements,
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
    public function ajouterHebergement(Request $request, HebergementRepository $hebergementRepository): Response
    {
        $hebergement = new Hebergement();
        $form = $this->createForm(HebergementFormeType::class, $hebergement);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($hebergement);
            $entityManager->flush();
            $nombreHebergements = $hebergementRepository->count([]);
            return $this->redirectToRoute('liste1', ['nombre_hebergements' => $nombreHebergements]);
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
// fonction eli ta3ti detail d'hebergement 
#[Route('/hebergement/{id}', name: 'hebergement_details')]
public function show($id, Request $request ,PaginatorInterface $paginator): Response
{
    $hebergement = $this->getDoctrine()->getRepository(Hebergement::class)->find($id);
    if (!$hebergement) {
        throw $this->createNotFoundException('Hébergement non trouvé');
    }
    $avisRepository = $this->getDoctrine()->getRepository(Avis::class);
    $query = $avisRepository->createQueryBuilder('a')
        ->where('a.hebergement = :hebergement')
        ->setParameter('hebergement', $hebergement)
        ->getQuery();/// hedhi query bech njibou les avis tet3mal fel repo  w ela toul houni

    $pagination = $paginator->paginate(
        $query, 
        $request->query->getInt('page', 1), //nmro mta3 el page
        3 //houni 9adeh theb min haja f wahda 
    );
    $avisRepository = $this->getDoctrine()->getRepository(Avis::class);
    $allAvis = $avisRepository->findBy(['hebergement' => $hebergement]);
    $avis = new Avis();
    $form = $this->createForm(AvisFormeType::class, $avis);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        $avis->setHebergement($hebergement);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($avis);
        $entityManager->flush();

        $this->addFlash('message', 'Your comment has been sent');
        return $this->redirectToRoute('hebergement_details', ['id' => $id]);
    }    
    return $this->render('hebergement/details1.html.twig', [
        'hebergement' => $hebergement,
        'allAvis' => $allAvis,
        'pagination' => $pagination,
        'form' => $form->createView(),
    ]);
}
    ///
    ///CRUD typehebergement
    #[Route('/types', name: 'types')]
    public function typesHebergement(TypeHebergementRepository $typeHebergementRepository): Response
    {
        $typesHebergement = $this->getDoctrine()->getRepository(TypeHebergement::class)->findAll();
        $nombretype = count($typesHebergement);

        return $this->render('hebergement/type.html.twig', [
            'typesHebergement' => $typesHebergement,
            'nombre_type' => $nombretype,
        ]);
    }
    #[Route('/addtype', name: 'addtype')]
    public function ajoutertype(Request $request, TypeHebergementRepository $typeHebergementRepository): Response
    {
        $typesHebergement = new TypeHebergement();
        $form = $this->createForm(TypeHebergementFormeType::class, $typesHebergement);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($typesHebergement);
            $entityManager->flush();
            $nombretype = $typeHebergementRepository->count([]);
            return $this->redirectToRoute('types', ['nombre_type' => $nombretype]);
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
   
    #[Route('/like/{hebergementId}/{action}', name: 'like')]
    public function like(Request $request, HebergementRepository $hebergementRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $hebergementId = $request->get('hebergementId');
        $action = $request->get('action');
        $hebergement = $hebergementRepository->find($hebergementId);
        if (!$hebergement) {
            return new JsonResponse(['success' => false, 'message' => 'Hebergement not found'], JsonResponse::HTTP_NOT_FOUND);
        }
        if ($action === 'add') {
            $hebergement->setnumber_likes($hebergement->getnumber_likes() + 1);
        } elseif ($action === 'remove') {
            $hebergement->setnumber_likes($hebergement->getnumber_likes() - 1);
        } else {
            return new JsonResponse(['success' => false, 'message' => 'Invalid action'], JsonResponse::HTTP_BAD_REQUEST);
        }
        $entityManager->flush();

        return new JsonResponse(['success' => true, 'likes' => $hebergement->getnumber_likes()]);
    }
    #[Route('/dislike/{hebergementId}/{action}', name: 'dislike')]
    public function dislike(Request $request, HebergementRepository $hebergementRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $hebergementId = $request->get('hebergementId');
        $action = $request->get('action');
        $hebergement = $hebergementRepository->find($hebergementId);
        
        if (!$hebergement) {
            return new JsonResponse(['success' => false, 'message' => 'Hebergement not found'], JsonResponse::HTTP_NOT_FOUND);
        }
        
        if ($action === 'add') {
            $hebergement->setnumber_dislikes($hebergement->getnumber_dislikes() + 1);
        } elseif ($action === 'remove') {
            $currentDislikes = $hebergement->getnumber_dislikes();
            if ($currentDislikes > 0) {
                $hebergement->setnumber_dislikes($currentDislikes - 1);
            } else {
                return new JsonResponse(['success' => false, 'message' => 'Invalid action'], JsonResponse::HTTP_BAD_REQUEST);
            }
        } else {
            return new JsonResponse(['success' => false, 'message' => 'Invalid action'], JsonResponse::HTTP_BAD_REQUEST);
        }
        
        $entityManager->flush();
        return new JsonResponse(['success' => true, 'dislikes' => $hebergement->getnumber_dislikes()]);
    }
    ////
    #[Route('/stats', name: 'stats')]
    public function statistiques(TypeHebergementRepository $typeHebergementRepo, HebergementRepository $hebergementRepo): Response {
        $types = $typeHebergementRepo->findAll();
        $typ = [];
        $typeColor = [];
        $typecount = [];
        foreach ($types as $type) {
            $typ[] = $type->getType();
            $typeColor[] = $type->getColor();
            $typecount[] = count($type->getHebergement());
        }
        $hebergements = $hebergementRepo->findAll();
        $hname = [];
        $likes = [];
        $dislikes = [];
        foreach ($hebergements as $hebergement) {
            $hname[] = $hebergement->getName();
            $likes[] = $hebergement->getnumber_likes();
            $dislikes[] = $hebergement->getnumber_dislikes();
        }
    
        return $this->render('hebergement/stats.html.twig', [
            'typ' => json_encode($typ),
            'typeColor' => json_encode($typeColor),
            'typecount' => json_encode($typecount),
            'hname' => json_encode($hname),
            'likes' => json_encode($likes),
            'dislikes' => json_encode($dislikes)
        ]);
    }
    ////search
    #[Route('/search', name: 'search')]
    public function searchAction(Request $request)
        {
            //the helper
            $em = $this->getDoctrine()->getManager();
            //9otlou jibli l haja hedhi
            $requestString = $request->get('q');
            //3amaliyet l recherche 
            $Hebergement = $em->getRepository('App\Entity\Hebergement')->findEntitiesByString($requestString);
            if(!$Hebergement) {
                $result['Hebergement']['error'] = "Don Not found 🙁 ";
            } else {
                $result['Hebergement'] = $this->getRealEntities($Hebergement);
            }
            return new Response(json_encode($result));
        }

        public function getRealEntities($Hebergement){
            //lhne 9otlou aala kol heber mawjouda jibli title wl taswira mte3ha
            foreach ($Hebergement as $Hebergement){
                $realEntities[$Hebergement->getId()] = [$Hebergement->getAdresse()];
    
            }
            return $realEntities;
        }
    ////
    #[Route('/calendrier/{idh}', name:'calendrier')]
    public function calendrier(int $idh,Request $request,  HebergementRepository $repo, ReservationHebergementRepository $resrepo):Response
    { 
$events=$resrepo->getreservationbyid($idh);
$calendardata=[];
foreach ($events as $event) {
    $calendardata[]=[
        'start' => $event->getDate()->format('Y-m-d H:i:s'),
        'end' => $event->getDuree()->format('Y-m-d H:i:s'),
        'title'=> 'Reserved :'. $event->getId()
    ];
}
$data=json_encode($calendardata);
return $this->render('/hebergement/calendrier.html.twig', ['data'=>$data]);
    }
    /////

/// CRUD avis ////sms///
}
