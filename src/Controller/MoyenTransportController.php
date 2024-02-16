<?php

namespace App\Controller;

use App\Entity\MoyenTransport;
use App\Form\MoyenTransportFormeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MoyenTransportController extends AbstractController
{
    #[Route('/moyen/transport', name: 'app_moyen_transport')]
    public function index(): Response
    {
        return $this->render('moyen_transport/index.html.twig', [
            'controller_name' => 'MoyenTransportController',
        ]);
    }


    #[Route('/addmoyen', name:'addMoyen')]
    public function addMoyen(\Doctrine\Persistence\ManagerRegistry $mr, Request $req): Response
    {
      
       $s=new MoyenTransport();
   
        $form=$this->createForm( MoyenTransportFormeType::class, $s);
        $form->handleRequest($req);


    if ($form->isSubmitted() && $form->isValid() ){
        $em=$mr->getManager();
        $em->persist($s); 
        $em->flush();
    
        
         return $this->redirectToRoute('moyensListe');}
        return $this->render('moyen_transport/addmoyen.html.twig', [
            'f'=>$form->createView()]);
    }

}
