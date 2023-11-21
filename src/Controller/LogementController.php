<?php

namespace App\Controller;

use App\Entity\Logement;
use App\Form\LogementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\LogementSearchType; 
use App\Repository\LogementRepository;

#[Route('/logement')]
class LogementController extends AbstractController
{
    
    #[Route('/', name: 'app_logement_index', methods: ['GET'])]
    public function index(Request $request, LogementRepository $logementRepository): Response
    {
        $searchCriteria = [
            'adrl' => $request->query->get('adrl'),
            'superfice' => $request->query->get('superfice'),
            'loyer' => $request->query->get('loyer'),
            'type' => $request->query->get('type'),
            'region' => $request->query->get('region'),
        ];
    
        $logements = $logementRepository->findBySearchCriteria($searchCriteria);
    
        return $this->render('logement/index.html.twig', [
            'logements' => $logements,
        ]);
    }
    #[Route('/client', name: 'app_logement_client_index', methods: ['GET'])]
    public function indexl(Request $request, LogementRepository $logementRepository): Response
    {
        $searchCriteria = [
            'adrl' => $request->query->get('adrl'),
            'superfice' => $request->query->get('superfice'),
            'loyer' => $request->query->get('loyer'),
            'type' => $request->query->get('type'),
            'region' => $request->query->get('region'),
        ];
    
        $logements = $logementRepository->findBySearchCriteria($searchCriteria);
    
        return $this->render('logement/index_client.html.twig', [
            'logements' => $logements,
        ]);
    }
    #[Route('/new', name: 'app_logement_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $logement = new Logement();
    $form = $this->createForm(LogementType::class, $logement);
    $form->handleRequest($request);
    $imagedirectory=$this->getParameter('kernel.project_dir').'/public/uploads';

    if ($form->isSubmitted() && $form->isValid()) {
        $imageFile = $form->get('image')->getData();

        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

            $imageFile->move($imagedirectory, $newFilename  );
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
    #[Route('/{idlogement}/client', name: 'app_logement_client_show', methods: ['GET'])]
    public function showClient(Logement $logement): Response
    {
        return $this->render('logement/show_client.html.twig', [
            'logement' => $logement,
        ]);
    }


#[Route('/{idlogement}/edit', name: 'app_logement_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Logement $logement, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(LogementType::class, $logement);
    $form->handleRequest($request);
    $imagedirectory = $this->getParameter('kernel.project_dir') . '/public/uploads';

    if ($form->isSubmitted() && $form->isValid()) {
        $imageFile = $form->get('image')->getData();

        if ($imageFile) {
            
            $oldImage = $logement->getImage();
            if ($oldImage) {
                $oldImagePath = $imagedirectory . '/' . $oldImage;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
            $imageFile->move($imagedirectory, $newFilename);
            $logement->setImage($newFilename);
        }

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
