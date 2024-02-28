<?php
namespace App\Controller;

use App\Entity\Event;
use App\Entity\Comment;
use App\Entity\User;    
use App\Form\EventFormeType;
use App\Form\CommentFormeType;
use App\Repository\UserRepository;
use App\Repository\EventRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class EventController extends AbstractController
{
    private $entityManager;
    private $managerRegistry;

    public function __construct(EntityManagerInterface $entityManager, ManagerRegistry $managerRegistry)
    {
        $this->entityManager = $entityManager;
        $this->managerRegistry = $managerRegistry;
    }
    #[Route('/event/index/{loc?}', name: 'event_index', methods: ['GET', 'POST'])]
    public function index(EventRepository $eventRepository, $loc = null, Request $request): Response
    {
        $countries = []; // Initialize an empty array to store countries
        $location = '';
    
        if ($loc !== null) {
            $location = (string) $loc;
        }
    
        $events = $eventRepository->findBy(['status' => 'Active']);
    
            foreach ($events as $event) {
                $eventLocation = $event->getEventLocation();
                $coordinates = explode(',', $eventLocation);
                $latitude = (float) $coordinates[1];
                $longitude = (float) $coordinates[0];
    
                $apiKey = '4e1ba267e158447ba011ec353f86f1a6';
                $url = "https://api.opencagedata.com/geocode/v1/json?q=$latitude+$longitude&key=$apiKey";
                $response = file_get_contents($url);
    
                if ($response !== false) {
                    $data = json_decode($response, true);
                    if (isset($data['results'][0]['components']['country'])) {
                        $countries[] = $data['results'][0]['components']['country']; // Store country in the array
                    }
                }
            }
    
        // Creating the form
        $event = new Event();
        $form = $this->createForm(EventFormeType::class, $event);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $event->setStatus('Pending');
            $event->setEventLocation($location);
    
            $userRepository = $this->managerRegistry->getRepository(User::class);
            $user = $userRepository->find($event->getUserCreator());
            $event->addParticipant($user);
    
            $this->entityManager->persist($event);
            $this->entityManager->flush();
    
            return $this->redirectToRoute('event_index');
        }
    
        return $this->render('event/index.html.twig', [
            'events' => $events,
            'country' => $countries, // Pass the countries array to the template
            'form' => $form->createView()
        ]);
    }
    


    #[Route('/event/validate/{id}', name: 'event_validate', methods: ['GET', 'POST'])]
    public function validate(Event $event, EntityManagerInterface $entityManager): Response
    {
        $event->setStatus('Active');
        $entityManager->flush();

        $this->addFlash('success', 'Event validated successfully!');

        return $this->redirectToRoute('event_show_back');
    }
    #[Route('/backE', name: 'backE')]
    public function backE()
    {  return $this->render('baseAdmin.html.twig');
    }
    #[Route('/map', name: 'map')]
    public function map()
    {  return $this->render('event/map.html.twig');
    }
    
#[Route('/emap/{id}', name: 'emap')]
public function emap( EventRepository $eventRepository, int $id): Response
{
    $event = $eventRepository->find($id);

    if (!$event) {
        throw $this->createNotFoundException('Event not found');
    }

    return $this->render('event/emap.html.twig', [
        'event' => $event,
        'id' => $id,
    ]);
}
#[Route('/event/showF', name: 'event_show_front')]
public function showFront(EventRepository $eventRepository, Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
{
    $events = $eventRepository->findBy(['status' => 'Active']);

    $country=null;
    foreach ($events as $event) {
        $eventLocation = $event->getEventLocation();
        $coordinates = explode(',', $eventLocation);
        $latitude = (float) $coordinates[1];
        $longitude = (float) $coordinates[0];

        $apiKey = '4e1ba267e158447ba011ec353f86f1a6';
        $url = "https://api.opencagedata.com/geocode/v1/json?q=$latitude+$longitude&key=$apiKey";
        $response = file_get_contents($url);

        if ($response !== false) {
            $data = json_decode($response, true);
            if (isset($data['results'][0]['components']['country'])) {
                $country = $data['results'][0]['components']['country'];
            }
        }
    }

    // Creating the form
    $event = new Event();
    $form = $this->createForm(EventFormeType::class, $event);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $user = $userRepository->find($event->getUserCreator());
        $event->addParticipant($user);
        $event->setStatus('Pending');

        $entityManager->persist($event);
        $entityManager->flush();

        return $this->redirectToRoute('event_index');
    }

    return $this->render('event/index.html.twig', [
        'country' => $country,
        'events' => $events,
        'form' => $form->createView()
    ]);
}


#[Route('/event/showBack', name: 'event_show_back')]
public function showBack(EventRepository $eventRepository): Response
{
    $country=null;
    $events = $eventRepository->findAll();
    // Fetching country information for each event
    foreach ($events as $event) {
        $eventLocation = $event->getEventLocation();
        $coordinates = explode(',', $eventLocation);

        // Check if coordinates are valid before accessing
        if (count($coordinates) >= 2) {
            $latitude = (float) $coordinates[1];
            $longitude = (float) $coordinates[0];

            $apiKey = '4e1ba267e158447ba011ec353f86f1a6';
            $url = "https://api.opencagedata.com/geocode/v1/json?q=$latitude+$longitude&key=$apiKey";
            $response = file_get_contents($url);

            if ($response !== false) {
                $data = json_decode($response, true);
                if (isset($data['results'][0]['components']['country'])) {
                    $country = $data['results'][0]['components']['country'];
                }
            }
        }
    }

    return $this->render('event/back.html.twig', [
        'events' => $events,
        'country' => $country ?? null // Initialize country in case no valid data is found
    ]);
}

    #[Route('/event/edit/{id}/{loc}', name: 'event_edit')]
    public function edit(Request $request, Event $event, $loc): Response
    {
        $form = $this->createForm(EventFormeType::class, $event);
    
        $location = (string) $loc;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $event->setEventLocation($location);
            $this->getDoctrine()->getManager()->flush();
    
            return $this->redirectToRoute('event_show_back');
        }
    
        // Add condition for handling null $loc
        if ($loc === null) {
            // Handle the case where $loc is null, for example, setting a default location
            $location = "Default Location";
        }
    
        return $this->render('event/edit.html.twig', [
            'events' => $event,
            'form' => $form->createView(),
            'loc' => $loc
        ]);
    }
    


    


    #[Route('/event/delete/{id}', name: 'event_delete')]
public function delete(Event $event, EntityManagerInterface $entityManager): Response
{
    $comments = $event->getComments();
    foreach ($comments as $comment) {
        $entityManager->remove($comment);
    }
    
    $entityManager->remove($event);
    $entityManager->flush();

    return $this->redirectToRoute('event_show_back');
}

    #[Route('/comment/{id}', name: 'comment_index', methods: ['GET', 'POST'])]
public function indexComment(CommentRepository $commentRepository, Request $request, $id, EntityManagerInterface $entityManager): Response
{
    // Retrieve event ID from the request parameters
    $eventId = (int) $id;

    if ($eventId <= 0) {
        $this->addFlash('error', 'Invalid event ID provided');
        return $this->redirectToRoute('event_index');
    }

    $event = $entityManager->getRepository(Event::class)->find($eventId);

    if (!$event) {
        throw $this->createNotFoundException('Event with id ' . $eventId . ' not found');
    }

    $comment = new Comment();
    $comment->setEvent($event);
    $currentDate = new \DateTime();
    $formattedDate = $currentDate->format('Y-m-d H:i:s');
    $comment->setDate($formattedDate);

    $form = $this->createForm(CommentFormeType::class, $comment);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($comment);
        $entityManager->flush();

        return $this->redirectToRoute('comment_index', ['id' => $event->getId()]);
    }

    $comments = $commentRepository->findBy(['event' => $event]);

    return $this->render('event/show.html.twig', [
        'id' => $event->getId(),
        'comments' => $comments,
        'event' => $event,
        'form' => $form->createView()
    ]);
}

    

    #[Route('/comment/show', name: 'comment_show_all')]
    public function showAllC(CommentRepository $commentRepository): Response
    {
        $comments = $commentRepository->findAll();

        return $this->render('event/show.html.twig', ['comments' => $comments]);
    }
    #[Route('/comment/{id}/edit', name: 'comment_edit', methods: ['GET', 'POST'])]
    public function editc(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentFormeType::class, $comment);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush(); 
    
            return $this->redirectToRoute('comment_show_all', ['id' => $comment->getId()]);
        }
    
        return $this->render('event/editc.html.twig', [
            'id' => $comment->getId(),
            'comments' => $comment,
            'form' => $form->createView()
        ]);
    }
    

#[Route('/comment/delete/{id}', name: 'comment_delete', methods: ['GET', 'POST'])]
public function deletec( Comment $comment ,EntityManagerInterface $entityManager): Response
{
    $entityManager->remove($comment);
    $entityManager->flush();

    return $this->redirectToRoute('comment_show_all');
}

}