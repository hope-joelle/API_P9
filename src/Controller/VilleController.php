<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VilleForm;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ville')]
final class VilleController extends AbstractController
{
    #[Route(name: 'app_ville_index', methods: ['GET'])]
    public function index(VilleRepository $villeRepository): Response
    {
        return $this->render('ville/index.html.twig', [
            'villes' => $villeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_ville_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ville = new Ville();
        $form = $this->createForm(VilleForm::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ville);
            $entityManager->flush();

            return $this->redirectToRoute('app_ville_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ville/new.html.twig', [
            'ville' => $ville,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ville_show', methods: ['GET'])]
    public function show(Ville $ville): Response
    {
        return $this->render('ville/show.html.twig', [
            'ville' => $ville,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ville_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ville $ville, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VilleForm::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ville_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ville/edit.html.twig', [
            'ville' => $ville,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ville_delete', methods: ['POST'])]
    public function delete(Request $request, Ville $ville, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ville->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($ville);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ville_index', [], Response::HTTP_SEE_OTHER);
    }
}
