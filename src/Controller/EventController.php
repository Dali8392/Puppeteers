<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;    
use App\Entity\Event;
use App\Entity\Comment;
use App\Form\EventFormeType;
use App\Form\CommentFormeType;
use App\Repository\CommentRepository;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class EventController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/event', name: 'event_index', methods: ['GET', 'POST'])]
    public function index(EventRepository $eventRepository, Request $request): Response
    {
        $events = $eventRepository->findAll();
        $event = new Event();
        $form = $this->createForm(EventFormeType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $event->addParticipant();
            $this->entityManager->persist($event);
            $this->entityManager->flush();

            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/index.html.twig', [
            'events' => $events,
            'form' => $form->createView()
        ]);
    }


    #[Route('/event/show', name: 'event_show_all')]
    public function showAll(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();
        $form = $this->createForm(EventFormeType::class); // Create form without data
        
        return $this->render('event/index.html.twig', [
            'events' => $events,
            'form' => $form->createView(), // Pass the form variable to the template
        ]);
    }
    

    #[Route('/event/{id}/edit', name: 'event_edit')]
    public function edit(Request $request, Event $event = null): Response
    {
        if (!$event) {
            $event = new Event(); // Corrected variable name from $events to $event
        }
    
        $form = $this->createForm(EventFormeType::class, $event);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($event); // Corrected variable name from $events to $event
            $this->entityManager->flush();
    
            return $this->redirectToRoute('event_index');
        }
    
        return $this->render('event/edit.html.twig', [
            'id' => $event->getId(),
            'events' => $event, // Corrected variable name from $events to $event
            'form' => $form->createView()
        ]);
    }
    


    #[Route('/event/{id}', name: 'event_delete')]
public function delete(Event $event, EntityManagerInterface $entityManager): Response
{
    $comments = $event->getComments();
    foreach ($comments as $comment) {
        $entityManager->remove($comment);
    }
    
    $entityManager->remove($event);
    $entityManager->flush();

    return $this->redirectToRoute('event_show_all');
}
#[Route('/add-participant/{eventId}', name: 'add_participant')]
    public function addParticipant(EventRepository $eventRepository, EntityManagerInterface $entityManager, $eventId): Response
    {
        // Retrieve an instance of Event
        $event = $eventRepository->find($eventId);

        // Check if the event exists
        if (!$event) {
            throw $this->createNotFoundException('Event not found');
        }

        // Get the user creator from the event
        $userCreator = $event->getUserCreator();

        // Add the user creator as a participant to the event
        $event->addParticipant($userCreator);

        // Persist and flush the changes
        $entityManager->persist($event);
        $entityManager->flush();

        // Redirect to a success page or wherever appropriate
        return $this->redirectToRoute('event_show_all', ['id' => $eventId]);
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

    // Create a new Comment entity and set its properties
    $comment = new Comment();
    $comment->setEvent($event);
    // Set the date to the current date as a string
    $currentDate = new \DateTime();
    $formattedDate = $currentDate->format('Y-m-d H:i:s');
    $comment->setDate($formattedDate);

    // Create the form using the CommentFormeType
    $form = $this->createForm(CommentFormeType::class, $comment);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Persist the comment entity
        $entityManager->persist($comment);
        $entityManager->flush();

        // Redirect to the comment index page with the event ID
        return $this->redirectToRoute('comment_index', ['id' => $event->getId()]);
    }

    // Retrieve comments associated with the event
    $comments = $commentRepository->findBy(['event' => $event]);

    // Render the template with the necessary data
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
        // Create the form for editing the comment
        $form = $this->createForm(CommentFormeType::class, $comment);
        $form->handleRequest($request);
    
        // Handle the form submission and update the comment
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush(); // Update the comment in the database
    
            // Redirect to the appropriate route after editing the comment
            return $this->redirectToRoute('comment_show_all', ['id' => $comment->getId()]);
        }
    
        // Render the form for editing the comment
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