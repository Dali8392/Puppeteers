<?php

namespace App\Controller;

use App\Entity\Guide;
use App\Form\GuideFormeType;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Repository\GuideRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/guide')]
class GuideController extends AbstractController
{

   
    #[Route('/', name: 'app_guide_index', methods: ['GET'])]

    public function index(GuideRepository $guideRepository): Response
    {
        return $this->render('guide/index.html.twig', [
            'guides' => $guideRepository->findAll(),
        ]);
    }
    #[Route('/recherche', name: 'app_guide_recherche', methods: ['GET'])]
    public function recherche(UserRepository $userRepository): Response
    {
        return $this->render('guide/recherche_guide.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

 

    #[Route('/{id}', name: 'app_guide_show', methods: ['GET'])]
    public function show(Guide $guide): Response
    {
        return $this->render('guide/show.html.twig', [
            'guide' => $guide,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_guide_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Guide $guide, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GuideFormeType::class, $guide);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_guide_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('guide/edit.html.twig', [
            'guide' => $guide,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_guide_delete', methods: ['POST'])]
    public function delete(Request $request, Guide $guide, EntityManagerInterface $entityManager): Response
    {
       
        $entityManager->remove($guide);
        $entityManager->flush();
    
        $this->addFlash('success', 'guide supprimée avec succès !');
        return $this->redirectToRoute('app_guide_index', [], Response::HTTP_SEE_OTHER);
    }

         #[Route("/guide/make-guide/{userId}", name:"app_guide_make_guide",methods:['POST'])]
         public function makeGuideAction(Request $request, EntityManagerInterface $em, string $userId): Response
         {
             // Fetch the user based on the provided ID
             $user = $em->getRepository(User::class)->find($userId);
         
             // Check if the user exists
             if (!$user) {
                 $this->addFlash('error', 'User not found!');
                 return $this->redirectToRoute('app_user_index');
             }
             $user->setRole('guide');

             // Create a new instance of Guide and set its properties
             $guide = new Guide();
             $guide->setCord($user);
         
             // Persist the guide entity
             $em->persist($guide);
             $em->flush();
         
             // Redirect to the edit page for the newly created guide
             return $this->redirectToRoute('app_guide_edit', ['id' => $guide->getId()]);
         }

    }
