<?php

namespace App\Controller;

use App\Entity\Hebergement;
use App\Entity\TypeHebergement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HebergementController extends AbstractController
{
    #[Route('/hebergement', name: 'app_hebergement')]
    public function index(SessionInterface $sessionInterface): Response
    {
        dd ($sessionInterface->has('id'));
    }
}