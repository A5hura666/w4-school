<?php

namespace App\Controller;

use App\Entity\Chapters;
use App\Repository\ChaptersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChapterController extends AbstractController
{
    #[Route('/chapter', name: 'app_chapter')]
    public function index(): Response
    {
        return $this->render('chapter/index.html.twig', [
            'controller_name' => 'ChapterController',
        ]);
    }

    #[Route('/chapter/{id}', name: 'app_chapter_show')]
    public function show($id, ChaptersRepository $chaptersRepository): Response
    {
        $chapter = $chaptersRepository->findBy(['id' => $id]);
        return $this->render('chapter/show.html.twig', [
            'controller_name' => 'ChapterController',
            'chapter' => $chapter[0],
        ]);
    }

    #[Route('/chapter/{id}/edit', name: 'app_chapter_edit')]
    public function edit($id, Request $request, ChaptersRepository $chaptersRepository, EntityManagerInterface $em): Response
    {
        // Récupérer le chapitre à modifier
        $chapter = $chaptersRepository->find($id);

        if (!$chapter) {
            throw $this->createNotFoundException('Le chapitre n\'existe pas');
        }

        // Créer le formulaire
        $form = $this->createForm(Chapters::class, $chapter);

        // Traiter la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer les modifications dans la base de données
            $em->persist($chapter);
            $em->flush();

            // Rediriger vers la page de détail du chapitre ou une autre page
            return $this->redirectToRoute('app_chapter_show', ['id' => $chapter->getId()]);
        }

        return $this->render('chapter/edit.html.twig', [
            'form' => $form->createView(),
            'chapter' => $chapter,
        ]);
    }
}