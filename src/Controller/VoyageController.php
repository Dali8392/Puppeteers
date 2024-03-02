<?php

namespace App\Controller;

use App\Entity\MoyenTransport;
use App\Entity\Voyage;
use App\Form\VoyageFormeType;
use App\Repository\MoyenTransportRepository;
use App\Repository\ReservationVoyageRepository;
use App\Repository\VoyageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;


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
            $events=$repo->findAll();
            $calvoyages=[];
            foreach( $events as $event ) {
                $start=new \DateTime($event->getDateDep()->format('Y-m-d') . ' ' . $event->getHeureDep()->format('H:i:s'));
                $title=$event->getDepart()."->".$event->getDestination();
                $end=new \DateTime($event->getDateArr()->format('Y-m-d') . ' ' . $event->getHeureArr()->format('H:i:s'));
                $calvoyages[]=[
                    'id'=> $event->getId(),
                    'start'=> $start->format('Y-m-d H:i:s'),
                    'end'=> $end->format('Y-m-d H:i:s'),
                    'title'=> $title,
                ];
            }
            $data=json_encode($calvoyages);
            return $this->render('voyage/listevoyages.html.twig', [
                'response' => $result,
                'data' => $data 
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
        
    if ($request->isMethod('post')){
        $dep=$request->get('depart') ; 
        $des=$request->get('destination') ; 
        $result=$repo->SearchVoyageByDepDes($dep,$des);
        // dd($result);
    }
    $events=$repo->findAll();
    $calvoyages=[];
    foreach( $events as $event ) {
        $start=new \DateTime($event->getDateDep()->format('Y-m-d') . ' ' . $event->getHeureDep()->format('H:i:s'));
        $title=$event->getDepart()."->".$event->getDestination();
        $end=new \DateTime($event->getDateArr()->format('Y-m-d') . ' ' . $event->getHeureArr()->format('H:i:s'));
        $calvoyages[]=[
            'id'=> $event->getId(),
            'start'=> $start->format('Y-m-d H:i:s'),
            'end'=> $end->format('Y-m-d H:i:s'),
            'title'=> $title,
        ];
    }
    $data=json_encode($calvoyages);
    
        // dd($result);
        return $this->render('voyage/listevoyages.html.twig', [
            'response'=>$result,
            'data' => $data ]);

    }


    #[Route('/indexvoyage', name:'indexvoyage')]
    public function showVoyage( VoyageRepository $repo): Response
    {
            $result=$repo->findAll();
            return $this->render('voyage/indexvoyage.html.twig', [
                'response' => $result,
            ]);
        }
    

        
    #[Route('/filtervoyage', name:'filtervoyage')]
    public function FilterVoyage(EntityManagerInterface $em, Request $request, VoyageRepository $repo): Response{
        $result=$repo->findAll();
   
    if ($request->isMethod('post')){
        $dep=$request->get('depart') ; 
        $des=$request->get('destination') ;
        $datedep= $request->get('dateDep');
        $budget= $request->get('budget');
        $result=$repo->FilterVoyages($dep,$des,$datedep, $budget);
       
    }
    
       // Render the Twig template as a string
    $html = $this->renderView('voyage/Voyagesfiltrés.html.twig', [
        'response' => $result
    ]);

    // Return the HTML as a response
    return new Response($html);
}



#[Route('/detailsvoyage/{id}', name:'detailsVoyage')]
public function showDetails(int $id, VoyageRepository $repo,ReservationVoyageRepository $rvrepo, \Doctrine\Persistence\ManagerRegistry $mr,ChartBuilderInterface $chartBuilder): Response
{
   $v=$repo->find($id);
   
   $endDate = new \DateTime();
   $startDate = (clone $endDate)->modify('-6 days');

   $chartlabels = [];
   $chartdata = [];
   $currentDate = $startDate;
   while ($currentDate <= $endDate) {
       $dateStr = $currentDate->format('Y-m-d');
       $chartlabels[] = $dateStr;
       $chartdata[] = $rvrepo->getNbrReservationsVoyageByDate($currentDate,$id);
       $currentDate->modify('+1 day');
   }

    
   return $this->render('voyage/detailsvoyage.html.twig', [
    't' => $v,
    'labels'=>json_encode($chartlabels),
    'data'=>json_encode($chartdata),
]);
}


    }