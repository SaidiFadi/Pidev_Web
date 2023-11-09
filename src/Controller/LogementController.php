<?php

namespace App\Controller;

use App\Entity\Logement;
use App\Form\LogementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/logement')]
class LogementController extends AbstractController
{
    #[Route('/', name: 'app_logement_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $logements = $entityManager
            ->getRepository(Logement::class)
            ->findAll();

        return $this->render('logement/index.html.twig', [
            'logements' => $logements,
        ]);
    }

    #[Route('/new', name: 'app_logement_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $logement = new Logement();
    $form = $this->createForm(LogementType::class, $logement);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Handle file upload
        $imageFile = $form->get('image')->getData();

        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

            // Move the file to the directory where images are stored
            $imageFile->move(
                $this->getParameter('images_directory'), // This should point to a directory within your public directory
                $newFilename
            );

            // Save the file name in the entity
            $logement->setImage($newFilename);
        }

        $entityManager->persist($logement);
        $entityManager->flush();

        return $this->redirectToRoute('app_logement_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('logement/new.html.twig', [
        'logement' => $logement,
        'form' => $form,
    ]);
}

    #[Route('/{idlogement}', name: 'app_logement_show', methods: ['GET'])]
    public function show(Logement $logement): Response
    {
        return $this->render('logement/show.html.twig', [
            'logement' => $logement,
        ]);
    }

    #[Route('/{idlogement}/edit', name: 'app_logement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Logement $logement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LogementType::class, $logement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_logement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('logement/edit.html.twig', [
            'logement' => $logement,
            'form' => $form,
        ]);
    }

    #[Route('/{idlogement}', name: 'app_logement_delete', methods: ['POST'])]
    public function delete(Request $request, Logement $logement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$logement->getIdlogement(), $request->request->get('_token'))) {
            $entityManager->remove($logement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_logement_index', [], Response::HTTP_SEE_OTHER);
    }
}
