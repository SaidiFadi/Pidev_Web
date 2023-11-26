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
use Knp\Snappy\Pdf;
use App\Service\TwilioSMSService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Security;





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
   #[Route('/client', name: 'app_location_client_index', methods: ['GET'])]
public function indexl(EntityManagerInterface $entityManager, Request $request): Response
{
    $email = $request->query->get('email');

    // Retrieve locations based on the provided email
    $locations = $entityManager
        ->getRepository(Location::class)
        ->findByEmail($email);

    return $this->render('location/index_client.html.twig', [
        'locations' => $locations,
    ]);
}
    #[Route('/new/{adrl}', name: 'app_location_news', methods: ['GET', 'POST'])]
    public function news(Request $request, EntityManagerInterface $entityManager, TwilioSMSService $twilioSMSService, string $adrl = null): Response
    {
        $location = new Location();
        $logement = null;
    
        if ($adrl) {
            $logement = $entityManager->getRepository(Logement::class)->findOneBy(['adrl' => $adrl]);
    
            if (!$logement) {
                throw $this->createNotFoundException('Logement not found');
            }
    
            $location->setLogement($logement);
        }
    
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Check for confli
            $conflictingReservations = $entityManager
                ->getRepository(Location::class)
                ->findConflictingReservations(
                    $location->getLogement(),
                    $location->getDatedebut(),
                    $location->getDatefin(),
                    $location->getIdlocation()
                );
    
            if ($conflictingReservations) {
                $this->addFlash('danger', 'This accommodation is already booked during the selected dates. Please choose another accommodation or change the dates.');
    
                return $this->redirectToRoute('app_location_conflict', [
                    'logementId' => $location->getLogement()->getIdlogement(),
                    'datedebut' => $location->getDatedebut()->format('Y-m-d'),
                    'datefin' => $location->getDatefin()->format('Y-m-d'),
                ]);
            }
    
            $this->calculateTarif($location);
            $entityManager->persist($location);
            $entityManager->flush();
    /*
            
            $phoneNumber = '+21650227451'; // Replace with phone number that you want to send SMS
            $email = $location->getPersonne()->getEmail();
            $adrl = $location->getLogement()->getAdrl();
            $dateDebut = $location->getDatedebut()->format('Y-m-d');
            $dateFin = $location->getDatefin()->format('Y-m-d');
            $tarif = $location->getTarif();
    
            $message = "New location added:\nEmail: $email\nAdrl: $adrl\nDate Debut: $dateDebut\nDate Fin: $dateFin\nTarif: $tarif";
    
            $twilioSMSService->sendSMS($phoneNumber, $message);
    */
    $this->addFlash('success', 'Location booked successfully!');

            return $this->redirectToRoute('app_logement_client_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('location/new.html.twig', [
            'location' => $location,
            'form' => $form,
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
                //  flash message
                $this->addFlash('danger', 'This accommodation is already booked during the selected dates. Please choose another accommodation or change the dates.');

                // Redirect to a specific route with a flash message
                return $this->redirectToRoute('app_location_conflict', [
                    'logementId' => $location->getLogement()->getIdlogement(), 
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
            $this->addFlash('danger', 'This accommodation is already booked during the selected dates. Please choose another accommodation or change the dates.');

            return $this->redirectToRoute('app_location_conflict', [
                'logementId' => $location->getLogement()->getIdlogement(),
                'datedebut' => $location->getDatedebut()->format('Y-m-d'),
                'datefin' => $location->getDatefin()->format('Y-m-d'),
            ]);
        }

        $this->calculateTarif($location); 
        $entityManager->flush();

        return $this->redirectToRoute('app_location_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('location/edit.html.twig', [
        'location' => $location,
        'form' => $form,
    ]);
}
  
#[Route('/{idlocation}/edit/client', name: 'app_location_client_edit', methods: ['GET', 'POST'])]
public function editclient(Request $request, Location $location, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(LocationType::class, $location);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $conflictingReservations = $entityManager
            ->getRepository(Location::class)
            ->findConflictingReservations(
                $location->getLogement(),
                $location->getDatedebut(),
                $location->getDatefin(),
                $location->getIdlocation()
            );

        if ($conflictingReservations) {
            $this->addFlash('danger', 'This accommodation is already booked during the selected dates. Please choose another accommodation or change the dates.');

            return $this->redirectToRoute('app_location_conflict', [
                'logementId' => $location->getLogement()->getIdlogement(),
                'datedebut' => $location->getDatedebut()->format('Y-m-d'),
                'datefin' => $location->getDatefin()->format('Y-m-d'),
            ]);
        }

        $this->calculateTarif($location); 
        $entityManager->flush();

        return $this->redirectToRoute('app_location_client_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('location/edit_client.html.twig', [
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
    #[Route('/{idlocation}/client', name: 'app_location_client_show', methods: ['GET'])]
    public function showclient(Location $location): Response
    {
        return $this->render('location/show_client.html.twig', [
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
    #[Route('/location/{idlocation}/pdf', name: 'app_location_pdf', methods: ['GET'])]
    public function generateLocationPdf(Location $location, Pdf $snappy): Response
    {
        // Customize the PDF content
        $html = $this->renderView('location/pdf_template.html.twig', [
            'location' => $location,
            // Add any other necessary variables for your PDF template
        ]);

        // Generate the PDF file
        $pdf = $snappy->getOutputFromHtml($html);

        // Set the response headers
        $response = new Response($pdf);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'inline; filename="location_' . $location->getIdlocation() . '.pdf"');

        return $response;
    }
    /*public function generatePdfAction(Request $request, Pdf $pdf): Response
    {
        $location = $this->getYourLocationLogic(); // Replace with your logic to get the location entity
    
        $html = $this->renderView('pdf_rental_agreement_template.html.twig', [
            'location' => $location,
        ]);
    
        $filename = sprintf('rental_agreement_%s.pdf', $location->getId());
    
        return new PdfResponse(
            $pdf->getOutputFromHtml($html),
            $filename,
            'application/pdf',
            ResponseHeaderBag::DISPOSITION_INLINE
        );
    }*/
    #[Route('/test-twilio-sms', name: 'test_twilio_sms')]
    public function testTwilioSMS(Location $location, TwilioSMSService $twilioSMSService): Response
    {
        $phoneNumber = '+21650227451'; // Make sure this is a valid Twilio test number
        $message = 'Hello from Twilio! This is a test SMS message.';
    
        // Send SMS
        $twilioSMSService->sendSMS($phoneNumber, $message);
    
        return $this->render('test_twilio_sms/index.html.twig', [
            'controller_name' => 'LocationController',
        ]);
    }
    public function validateDates(ExecutionContextInterface $context, $payload)
    {
        $conflictingReservations = $this->logement->getConflictingReservations($this->datedebut, $this->datefin, $this->idlocation);

        if ($conflictingReservations) {
            $context->buildViolation('This accommodation is already booked during the selected dates.')
                ->atPath('datedebut')
                ->addViolation();
        }
    }
}
