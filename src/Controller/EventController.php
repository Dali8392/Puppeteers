<?php

namespace App\Controller;

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

        return $this->render('event/index.html.twig', ['events' => $events]);
    }

    #[Route('/event/{id}/edit', name: 'event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event): Response
    {
        $form = $this->createForm(EventFormeType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/event/{id}', name: 'event_delete')]
    public function delete(Event $event): Response
    {
        $this->entityManager->remove($event);
        $this->entityManager->flush();

        return $this->redirectToRoute('event_show_all');
    }
    #[Route('/comment', name: 'comment_index', methods: ['GET', 'POST'])]
    public function indexC(CommentRepository $commentRepository, Request $request): Response
    {
        $comments = $commentRepository->findAll();
        $comment = new comment();
        $form = $this->createForm(CommentFormeType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return $this->redirectToRoute('comment_index');
        }

        return $this->render('event/show.html.twig', [
            'comments' => $comments,
            'form' => $form->createView()
        ]);
    }
}
