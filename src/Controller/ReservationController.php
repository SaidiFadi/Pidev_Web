<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Evenement;
use App\Entity\Personne;
use App\Form\ReservationType;
use App\Form\EvenementType;
use App\Repository\ReservationRepository;
use App\Repository\EvenementRepository;
use App\Repository\PersonneRepository;
use App\Services\QrcodeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Service\EmailSender;use Endroid\QrCode\Encoding\Encoder;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;




//#[Route('/reservation')]
class ReservationController extends AbstractController
{

    #[Route('/add/{idevt}', name: 'app_reservation_add')]
    public function add(Request $request, EntityManagerInterface $entityManager, $idevt): Response
    {
        // Récupérer l'événement basé sur $idevt
    $evenementRepository = $this->getDoctrine()->getRepository(Evenement::class);
    $evenement = $evenementRepository->find($idevt);

    // Vérifier si l'événement existe
    if (!$evenement) {
        throw $this->createNotFoundException('L"événement n"existe pas');
    }

    // Créer une nouvelle réservation
    $reservation1 = new Reservation();
    $reservation1->setTitreevt($evenement->getTitreevt());

    // Créer le formulaire de réservation
    $form = $this->createForm(ReservationType::class, $reservation1);
    $form->handleRequest($request);

    // Vérifier si le formulaire a été soumis et est valide
    if ($form->isSubmitted() && $form->isValid()) {
        // Ajouter la réservation à la base de données
        $reservation1->setIdevt($evenement); // Associer la réservation à l'événement
        $entityManager->persist($reservation1);
        $entityManager->flush();

        $this->addFlash('success', 'Réservation ajoutée avec succès.');

        // Rediriger vers la page de l'événement ou une autre page appropriée
        return $this->redirectToRoute('app_evenement_showCl', ['idevt' => $idevt]);
    }

    // Afficher le formulaire de réservation
    return $this->render('reservation/frontres.html.twig', [
        'reservation1' => $reservation1,
        'form' => $form->createView(),
        'evenements' => $evenement,
        'button_label' => 'Save',
    ]);
    }
    


#[Route('/reserve/{idevt}', name: 'app_reservation_reserve' , methods: ['GET', 'POST'])]
public function reserver(EntityManagerInterface $entityManager, int $idevt ): Response
{
    $existingPersonne = $entityManager->getRepository(Personne::class)->find(39);

    $selectedEvent = $entityManager->getRepository(Evenement::class)->find($idevt);
    if(!$selectedEvent)
    {
//handle 
    }
   //$existingEvent= $entityManager->getRepository(Reservation::class)->find

   $reservation=new Reservation();
   $reservation->setId($existingPersonne);
   $reservation->setIdevt($selectedEvent);
   $reservation->setTitreevt($selectedEvent->getTitreevt());
   $reservation->setPrixbillet(0);
   $reservation->setImageres(0);
   //$reservation->setTitreevt('titre');


   $entityManager->persist($reservation);
   $entityManager->flush();

   return $this->redirectToRoute('app_evenement_showCl', ['idevt' => $idevt]);

    }

    #[Route("/generate_qr_page/{idevt}/{idBillet}", name: "generate_qr_page")]
    public function generateQRPage(Request $request, Reservation $reservation, QrcodeService $qrcodeService, $idevt, $idBillet): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $evenements = $entityManager->getRepository(Evenement::class)->find($idevt);
    
        // Assuming you have some data to generate the QR code
        $data = $evenements->getTitreevt() . ' | Date: ' . $evenements->getDateevt()->format('d-m-Y') . ' | Adresse: ' . $evenements->getAdresseevt() . ' | ID Billet: ' . $reservation->getIdBillet();
        $qrCodeDataUri = $qrcodeService->qrcode($evenements->getTitreevt());
    
        // Render the template with data
        return $this->render('reservation/myQR.html.twig', [
            'evenements' => $evenements,
            'qrImageDataUri' => $qrCodeDataUri,
            'reservation' => $reservation,
        ]);
    }
    
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(Request $request,ReservationRepository $reservationRepository): Response
    {  
        
        $form = $this->createForm(ReservationType::class);
        $form->handleRequest($request);
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
            'button_label' => 'Save',
        ]);
    }

    #[Route('/{idBillet}', name: 'app_reservation_show', methods: ['GET'])]
public function show(Reservation $reservation): Response
{
    return $this->render('reservation/show.html.twig', [
        'reservation' => $reservation,
    ]);
}


#[Route('/{idBillet}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{idBillet}', name: 'app_reservation_delete', methods: ['POST'])]
public function delete(Request $request, $idBillet, EntityManagerInterface $entityManager): Response
{
    $reservation = $entityManager->getRepository(Reservation::class)->find($idBillet);

    if (!$reservation) {
        // Handle the case where the reservation is not found
        throw $this->createNotFoundException('The reservation does not exist.');
    }

    if ($this->isCsrfTokenValid('delete'.$reservation->getIdBillet(), $request->request->get('_token'))) {
        $entityManager->remove($reservation);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_mesparticipations', [], Response::HTTP_SEE_OTHER);
}

#[Route('/{id}/{idevt}/del', name: 'app_reservationn_cancel', methods: ['GET', 'POST'])]
public function annulerReservation($id, $idevt,EntityManagerInterface $entityManager, ReservationRepository $reservationRepository,SessionInterface $session): Response
{
    $evenementRepository = $this->getDoctrine()->getRepository(Evenement::class);
    $evenement = $evenementRepository->find($idevt);

    $personneRepository = $this->getDoctrine()->getRepository(Personne::class);
    $id= $personneRepository->find($id);

    // Rechercher la participation de l'utilisateur à l'événement
    $reservation = $reservationRepository->findOneBy([
        'id' => $id,
        'idevt' => $evenement,
    ]);

    if ($reservation) {
        // Supprimer la participation
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($reservation);
        $entityManager->flush();

        $this->addFlash('success', 'Votre reservation a été annulée avec succès !');
    } else {
        $this->addFlash('error', 'Vous ne réservez pas encore cet événement.');
    }

   $subject = 'Annulation de reservation est effectueé';
   $body = 'Bonjour, malheuresement vous avez annulé votre reservation à l"evenement '.$evenement->getTitreevt().' avec succes';
   $id = $entityManager
    ->getRepository(Personne::class)
    ->find($id);
   // Rediriger l'utilisateur vers la page de l'événement

   return $this->render('reservation/mesReservations.html.twig', [
       'evenement' => $evenement,
       'reservation' => $reservation,
       'id' => $id,
   ]);
}


#[Route('/{idevt}/reserve', name: 'app_reservation_reserve', methods: ['GET', 'POST'])]
public function ajouterReservation($idevt, ReservationRepository $reservationRepository, SessionInterface $session): Response
{
    // Get user ID from the session or set a default value (e.g., null)
    $id = 23;

    // Check if $id is not null
    if ($id === null) {
        // Handle the case when $id is null (e.g., redirect to a login page)
        $this->addFlash('error', 'You must be logged in to reserve an event.');
        return $this->redirectToRoute('app_reservation_index');
    }

    $entityManager = $this->getDoctrine()->getManager();
    $evenementRepository = $this->getDoctrine()->getRepository(Evenement::class);
    $evenement = $evenementRepository->find($idevt);

    $personneRepository = $this->getDoctrine()->getRepository(Personne::class);
    $personne = $personneRepository->find($id);


    $reservationExistante = $reservationRepository->findBy([
        'id' => $personne,
        'idevt' => $evenement,
    ]);

    if (!empty($reservationExistante)) {
        $this->addFlash('error', 'You have already reserved this event.');
        return $this->redirectToRoute('app_evenement_indexCl');
    }

    $reservation = new Reservation();
    $reservation->setId($personne);
    $reservation->setIdevt($evenement);
    
    // Set other required fields for Reservation
    $reservation->setTitreevt($evenement->getTitreevt());

    $entityManager->persist($reservation);
    $entityManager->flush();

    $subject = 'Reservation successful';
    $body = 'Hello, your reservation for the event ' . $evenement->getTitreevt() . ' has been successful.';
    $to = $personne->getEmail();
    // Assuming $emailService is an instance of your email service class

    $this->addFlash('success', 'Your reservation has been recorded successfully!');

    return $this->redirectToRoute('app_evenement_indexCl');
}


#[Route('/{id}/participations', name: 'app_mesparticipations', methods: ['GET', 'POST'])]
public function mesParticipations($id,EvenementRepository $evenementRepository,ReservationRepository $reservationRepository): Response
{
    // Set a static user ID (e.g., 23)s
    // Assuming you have a method to find evenements by user ID in your repository
    $evenement = $evenementRepository->findEvenementsByUserId($id);
    $reservations = $reservationRepository->findEvenementsByUserId($id);

    return $this->render('reservation/mesReservations.html.twig', [
        'evenement' => $evenement,
        'reservations'=> $reservations,
        'id' => $id,
    ]);
}

#[Route('/{idevt}/front', name: 'app_reservation_showCl', methods: ['GET'])]
public function showResClient(Evenement $evenement, ReservationRepository $reservationRepository,EntityManagerInterface $em, $idevt): Response
{
    $reservation = $em->getRepository(Reservation::class)->find($idevt);

return $this->render('reservation/frontRes.html.twig', [
    'evenements' => $evenement,
    'reservation' => $reservation,
]);
}

}