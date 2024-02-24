<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Form\ActiviteFormeType;
use App\Repository\ActiviteRepository;
use App\Repository\GuideRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/activite')]
class ActiviteController extends AbstractController
{
    #[Route('/userindex', name: 'app_activite_user_index', methods: ['GET'])]
    public function userindex(ActiviteRepository $activiteRepository, Request $request)
{
    $searchTerm = $request->query->get('search');

    $activities = $activiteRepository->findAll();

    $filteredActivities = array_filter($activities, function ($activity) use ($searchTerm) {
        return strpos($activity->getVille(), $searchTerm) !== false;
    });

    $totalActivities = count($activities);

    return $this->render('activite/index_user.html.twig', [
        'activites' => $filteredActivities,
        'search' => $searchTerm,
        'totalActivities' => $totalActivities,
    ]);
}
    #[Route('/', name: 'app_activite_index', methods: ['GET'])]
    public function index(ActiviteRepository $activiteRepository, Request $request)
{
    $searchTerm = $request->query->get('search');

    $activities = $activiteRepository->findAll();

    $filteredActivities = array_filter($activities, function ($activity) use ($searchTerm) {
        return strpos($activity->getVille(), $searchTerm) !== false;
    });

    $totalActivities = count($activities);

    return $this->render('activite/index.html.twig', [
        'activites' => $filteredActivities,
        'search' => $searchTerm,
        'totalActivities' => $totalActivities,
    ]);
}
    #[Route('/accepter', name: 'app_activite_accepter', methods: ['GET'])]
    public function accepter(ActiviteRepository $activiteRepository): Response
    {
        return $this->render('activite/review_activite_form.html.twig', [
            'activites' => $activiteRepository->findAll(),
        ]);
    }

    #[Route('/{id}/valider', name: 'app_activite_valider', methods: ['GET'])]

    public function valider(Request $request, $id)
    {
        $activite = $this->getDoctrine()->getRepository(Activite::class)->find($id);
    
        if (!$activite) {
            return new Response('Activite not found', Response::HTTP_NOT_FOUND);
        }
    
        $activite->setEtat(1); 
        $em = $this->getDoctrine()->getManager();
        $em->persist($activite);
        $em->flush();
    
        $this->addFlash('success', 'Activite accepted successfully!');
    
        return $this->redirectToRoute('app_activite_accepter'); 
    }



    #[Route('/{id}/edit', name: 'app_activite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Activite $activite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ActiviteFormeType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_activite_index');
        }

        return $this->renderForm('activite/edit.html.twig', [
            'activite' => $activite,
            'form' => $form,
        ]);
    }





    #[Route('/new', name: 'app_activite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $activite = new Activite();
        $form = $this->createForm(ActiviteFormeType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($activite);
            $entityManager->flush();

            return $this->redirectToRoute('app_activite_index');
        }
        

        return $this->renderForm('activite/new.html.twig', [
            'activite' => $activite,
            'form' => $form,
        ]);
    }







    #[Route('/{id}/show', name: 'app_activite_show', methods: ['GET'])]
    public function show(Activite $activite,GuideRepository $guideRepository): Response
    {
        return $this->render('activite/show.html.twig', [
            'activite' => $activite,
            'guides' => $guideRepository->findAll()
        ]);
    }




    
    #[Route('/{id}/show_user', name: 'app_activite_user_show', methods: ['GET'])]
    public function show_user(Activite $activite,GuideRepository $guideRepository): Response
    {
        return $this->render('activite/show_user.html.twig', [
            'activite' => $activite,
            'guides' => $guideRepository->findAll()
        ]);
    }





   




    #[Route('/{id}', name: 'app_activite_delete', methods: ['GET','POST'])]
    public function delete(Request $request, Activite $activite, EntityManagerInterface $entityManager): Response
{
    $entityManager->remove($activite);
    $entityManager->flush();

    $this->addFlash('success', 'Activité supprimée avec succès !');

    return $this->redirectToRoute('app_activite_index');
}
    
   





}
