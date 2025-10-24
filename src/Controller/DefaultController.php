<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CompetitorRepository;

final class DefaultController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }

    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(CompetitorRepository $competitorRepository): Response
    {
        return $this->render('dashboard.html.twig', [
            'competitors' => $competitorRepository->findBy(['user'=>$this->getUser()]),
        ]);
    }
}
