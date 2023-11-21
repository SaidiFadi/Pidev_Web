<?php

namespace App\Controller;

use App\Entity\Location;
use App\Form\LocationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\EntityType;
use App\Entity\Logement;


#[Route('/location')]
class LocationController extends AbstractController
{
    #[Route('/', name: 'app_location_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $locations = $entityManager
            ->getRepository(Location::class)
            ->findAll();

        return $this->render('location/index.html.twig', [
            'locations' => $locations,
        ]);
    }

    #[Route('/new', name: 'app_location_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $location = new Location();
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check for conflicting reservations before persisting
            $conflictingReservations = $entityManager
                ->getRepository(Location::class)
                ->findConflictingReservations(
                    $location->getLogement(),
                    $location->getDatedebut(),
                    $location->getDatefin(),
                    $location->getIdlocation()
                );

            if ($conflictingReservations) {
                // Display a flash message
                $this->addFlash('danger', 'This accommodation is already booked during the selected dates. Please choose another accommodation or change the dates.');

                // Redirect to a specific route with a flash message
                return $this->redirectToRoute('app_location_conflict', [
                    'logementId' => $location->getLogement()->getIdlogement(), // Make sure to use getIdlogement()
                    'datedebut' => $location->getDatedebut()->format('Y-m-d'),
                    'datefin' => $location->getDatefin()->format('Y-m-d'),
                ]);
            }

            $this->calculateTarif($location); 
            
            $entityManager->persist($location);
            $entityManager->flush();

            return $this->redirectToRoute('app_location_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('location/new.html.twig', [
            'location' => $location,
            'form' => $form,
        ]);
    }

    #[Route('/conflict', name: 'app_location_conflict', methods: ['GET'])]
    public function conflict(Request $request): Response
    {
        $logementId = $request->query->get('logementId');
        $datedebut = $request->query->get('datedebut');
        $datefin = $request->query->get('datefin');

        // You may need to fetch the logement and other necessary data based on the provided parameters
        // Fetch the logement
        $logement = $this->getDoctrine()->getRepository(Logement::class)->find($logementId);

        return $this->render('location/conflict.html.twig', [
            'logement' => $logement,
            'datedebut' => $datedebut,
            'datefin' => $datefin,
        ]);
    }


    #[Route('/{idlocation}/edit', name: 'app_location_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Location $location, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->calculateTarif($location); 
            
            $entityManager->flush();

            return $this->redirectToRoute('app_location_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('location/edit.html.twig', [
            'location' => $location,
            'form' => $form,
        ]);
    }

    private function calculateTarif(Location $location): void
    {
        $datedebut = $location->getDatedebut();
        $datefin = $location->getDatefin();
        $rent = $location->getLogement()->getLoyer();
        $numberOfDays = $datedebut->diff($datefin)->days;
        $tarif = $numberOfDays * $rent;

        $location->setTarif($tarif);
    }

    #[Route('/{idlocation}', name: 'app_location_show', methods: ['GET'])]
    public function show(Location $location): Response
    {
        return $this->render('location/show.html.twig', [
            'location' => $location,
        ]);
    }

    

    #[Route('/{idlocation}', name: 'app_location_delete', methods: ['POST'])]
    public function delete(Request $request, Location $location, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$location->getIdlocation(), $request->request->get('_token'))) {
            $entityManager->remove($location);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_location_index', [], Response::HTTP_SEE_OTHER);
    }
   
    #[Route('/news/{adrl}', name: 'app_location_news', methods: ['GET', 'POST'])]
public function news(Request $request, EntityManagerInterface $entityManager, string $adrl): Response
{
    // Fetch the Logement entity using the adrl parameter
    $logement = $entityManager->getRepository(Logement::class)->findOneBy(['adrl' => $adrl]);

    if (!$logement) {
        throw $this->createNotFoundException('Logement not found');
    }

    $location = new Location();
    $location->setLogement($logement);

    // Pass the Logement entity to the LocationType form
    $form = $this->createForm(LocationType::class, $location);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $this->calculateTarif($location);

        $entityManager->persist($location);
        $entityManager->flush();

        return $this->redirectToRoute('app_location_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('location/new.html.twig', [
        'location' => $location,
        'form' => $form,
    ]);
}
}
