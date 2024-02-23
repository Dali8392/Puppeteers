<?php

namespace App\Controller;

use App\Entity\MoyenTransport;
use App\Form\MoyenTransportFormeType;
use App\Repository\MoyenTransportRepository;
use App\Repository\VoyageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


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
    
        
         return $this->redirectToRoute('moyensList');}
        return $this->render('moyen_transport/addmoyen.html.twig', [
            'f'=>$form->createView()]);
    }



    #[Route('/moyenslist', name:'moyensList')]
    public function fetch( MoyenTransportRepository $repo): Response
    {
            $result=$repo->findAll();
            return $this->render('moyen_transport/listemoyens.html.twig', [
                'response' => $result,
            ]);
        }



        #[Route('/removemoyen/{id}', name:'removeMoyen')]
    public function remove(int $id, MoyenTransportRepository $repo, VoyageRepository $voyagerepo, \Doctrine\Persistence\ManagerRegistry $mr): Response
    {
       $s=$repo->find($id);
       $em=$mr->getManager();
       $voyagesset= $voyagerepo->SearchVoyageByTransport($id);
       
        foreach($voyagesset as $voyage){
            $voyage->setMoyenTransport(null);
            $em->persist($voyage);
        }
        $em->remove($s);
        $em->flush();
        
        return $this->redirectToRoute('moyensList');
    }

    #[Route('/updateMoyen/{id}', name:'updateMoyen')]
    public function updateVoyage(\Doctrine\Persistence\ManagerRegistry $mr, MoyenTransportRepository $moyenrepo,MoyenTransportRepository $repo, Request $req, int $id): Response
    {
        $em=$mr->getManager();
          $s=$repo->find($id);
        $form=$this->createForm( MoyenTransportFormeType::class, $s);
        $form->handleRequest($req);


    if ($form->isSubmitted() && $form->isValid() ){
        
        $em->flush();
       
         return $this->redirectToRoute('moyensList');}
        return $this->render('moyen_transport/updatemoyen.html.twig', [
            'f'=>$form->createView()]);
    }



    #[Route('/searchmoyen', name:'searchmoyen')]
    public function SearchMoyen(EntityManagerInterface $em, Request $request, MoyenTransportRepository $repo): Response{
        $result=$repo->findAll();
    
    if ($request->isMethod('post')){
        $model=$request->get('idmodele') ; 
        $type=$request->get('typeMoyen') ; 
        $result=$repo->SearchMoyenByModelOrType($model,$type);
        
    }
    
        // dd($result);
        return $this->render('moyen_transport/listemoyens.html.twig', [
            'response'=>$result]);

    }

}
