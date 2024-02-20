<?php

namespace App\Controller;

use App\Entity\MoyenTransport;
use App\Entity\Voyage;
use App\Form\VoyageFormeType;
use App\Repository\MoyenTransportRepository;
use App\Repository\VoyageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Doctrine\Persistence\ManagerRegistry;


class VoyageController extends AbstractController
{
    #[Route('/voyage', name: 'app_voyage')]
    public function index(): Response
    {
        return $this->render('voyage/index.html.twig', [
            'controller_name' => 'VoyageController',
        ]);
    }



    #[Route('/voyageslist', name:'VoyagesList')]
    public function fetch( VoyageRepository $repo): Response
    {
            $result=$repo->findAll();
            return $this->render('voyage/listevoyages.html.twig', [
                'response' => $result,
            ]);
        }
    

    #[Route('/addvoyage', name:'addVoyage')]
    public function addVoyage(\Doctrine\Persistence\ManagerRegistry $mr, Request $req): Response
    {
      
       $s=new Voyage();
   
        $form=$this->createForm( VoyageFormeType::class, $s);
        $form->handleRequest($req);


    if ($form->isSubmitted() && $form->isValid() ){
        $em=$mr->getManager();
        $em->persist($s); 
        $em->flush();
    
        
         return $this->redirectToRoute('VoyagesList');}
        return $this->render('voyage/addvoyage.html.twig', [
            'f'=>$form->createView()]);
    }


    
    #[Route('/removeVoyage/{id}', name:'removeVoyage')]
    public function remove(int $id, VoyageRepository $repo, \Doctrine\Persistence\ManagerRegistry $mr): Response
    {
       $s=$repo->find($id);
       $em=$mr->getManager();
        $em->remove($s);
        $em->flush();


        
        return $this->redirectToRoute('VoyagesList');
    }

    #[Route('/updateVoyage/{id}', name:'updateVoyage')]
    public function updateVoyage(\Doctrine\Persistence\ManagerRegistry $mr, MoyenTransportRepository $moyenrepo,VoyageRepository $repo, Request $req, int $id): Response
    {
        $em=$mr->getManager();
          $s=$repo->find($id);
        $form=$this->createForm( VoyageFormeType::class, $s);
        $form->handleRequest($req);


    if ($form->isSubmitted() && $form->isValid() ){
        
        $em->flush();
       

         return $this->redirectToRoute('VoyagesList');}
        return $this->render('voyage/updatevoyage.html.twig', [
            'f'=>$form->createView()]);
    }



    #[Route('/searchvoyage', name:'searchvoyage')]
    public function SearchVoyage(EntityManagerInterface $em, Request $request, VoyageRepository $repo): Response{
        $result=$repo->findAll();
    //     $req= $em->createQuery(" select s.nom from App\Entity\Student s where s.nom=:n");
    // //select * from student
    // if ($request->isMethod("post")){
    // $value=$request->get('test') ;   
    // $req->setParameter('n',$value);
    // $result=$req->getResult();
    // }
    if ($request->isMethod('post')){
        $dep=$request->get('depart') ; 
        $des=$request->get('destination') ; 
        $result=$repo->SearchVoyageByDepDes($dep,$des);
        // dd($result);
    }
    
        // dd($result);
        return $this->render('voyage/listevoyages.html.twig', [
            'response'=>$result]);

    }


    #[Route('/indexvoyage', name:'indexvoyage')]
    public function showVoyage( VoyageRepository $repo): Response
    {
            $result=$repo->findAll();
            return $this->render('voyage/indexvoyage.html.twig', [
                'response' => $result,
            ]);
        }
    

}