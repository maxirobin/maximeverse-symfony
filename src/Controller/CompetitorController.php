<?php

namespace App\Controller;

use App\Entity\Competitor;
use App\Form\CompetitorType;
use App\Repository\CompetitorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dashboard/competitor')]
final class CompetitorController extends AbstractController
{
    #[Route('/new', name: 'app_competitor_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $competitor = new Competitor();
        $competitor->SetUser($this->getUser());
        $form = $this->createForm(CompetitorType::class, $competitor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($competitor);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('competitor/new.html.twig', [
            'competitor' => $competitor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_competitor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Competitor $competitor, EntityManagerInterface $entityManager): Response
    {
        if($competitor->getUser()!=$this->getUser()) { // Si ça ne lui appartient pas
            return $this->redirectToRoute('dashboard');
        }

        $form = $this->createForm(CompetitorType::class, $competitor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('competitor/edit.html.twig', [
            'competitor' => $competitor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_competitor_delete', methods: ['POST'])]
    public function delete(Request $request, Competitor $competitor, EntityManagerInterface $entityManager): Response
    {
        if($competitor->getUser()!=$this->getUser()) { // Si ça ne lui appartient pas
            return $this->redirectToRoute('dashboard');
        }

        if ($this->isCsrfTokenValid('delete'.$competitor->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($competitor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('dashboard', [], Response::HTTP_SEE_OTHER);
    }
}
