<?php

namespace App\Controller;

use App\Entity\Commune;
use App\Form\CommuneForm;
use App\Repository\CommuneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/commune')]
final class CommuneController extends AbstractController
{
    #[Route(name: 'app_commune_index', methods: ['GET'])]
    public function index(CommuneRepository $communeRepository): Response
    {
        return $this->render('commune/index.html.twig', [
            'communes' => $communeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_commune_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commune = new Commune();
        $form = $this->createForm(CommuneForm::class, $commune);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commune);
            $entityManager->flush();

            return $this->redirectToRoute('app_commune_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commune/new.html.twig', [
            'commune' => $commune,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commune_show', methods: ['GET'])]
    public function show(Commune $commune): Response
    {
        return $this->render('commune/show.html.twig', [
            'commune' => $commune,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commune_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commune $commune, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommuneForm::class, $commune);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commune_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commune/edit.html.twig', [
            'commune' => $commune,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commune_delete', methods: ['POST'])]
    public function delete(Request $request, Commune $commune, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commune->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($commune);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commune_index', [], Response::HTTP_SEE_OTHER);
    }
}
