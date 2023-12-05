<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Reservation;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


#[Route('/evenement')]
class EvenementController extends AbstractController
{
    #[Route('/', name: 'app_evenement_indexAdmin', methods: ['GET'])]
    public function index(Request $request, EvenementRepository $evenementRepository): Response
    {
        
        $searchCriteria = [
            'titreevt' => $request->query->get('titreevt'),
            'descevt' => $request->query->get('descevt'),
            'adresseevt' => $request->query->get('adresseevt'),
            ];
        
            $evenements = $evenementRepository->findBySearchCriteria($searchCriteria);
        
            return $this->render('evenement/indexAdmin.html.twig', [
                'evenements' => $evenements,
            ]);
        }
#[Route('/allCl', name: 'app_evenement_indexCl', methods: ['GET'])]
public function indexClient(Request $request, EvenementRepository $evenementRepository,
ReservationRepository $reservationRepository): Response
{
    $searchCriteria = [
        'titreevt' => $request->query->get('titreevt'),
        'descevt' => $request->query->get('descevt'),
        'adresseevt' => $request->query->get('adresseevt'),
        
    ];
    
    // Create the form
    $form = $this->createForm(EvenementType::class);
    $form->handleRequest($request);
    


    $evenements = $evenementRepository->findBySearchCriteria($searchCriteria);
    $reservations = $reservationRepository->findAll(); // Fetch reservation data


    return $this->render('evenement/indexCl.html.twig', [
        'evenements' => $evenements,
        'reservations' => $reservations,
        ]);
}

#[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
{
    $evenement = new Evenement();
    $form = $this->createForm(EvenementType::class, $evenement);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $imageFile = $form->get('imageevt')->getData();
        $videoFile = $form->get('videoevt')->getData();

        if ($imageFile) {
            $newImageFilename = uniqid().'.'.$imageFile->guessExtension();
            $imageFile->move(
                $this->getParameter('image_directory'),
                $newImageFilename
            );

            $evenement->setImageEvt($newImageFilename);
        }

        if ($videoFile) {
            $originalFilename = pathinfo($videoFile->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
            $newVideoFilename = $safeFilename.'-'.uniqid().'.'.$videoFile->guessExtension();

            // Move the file to the directory where videos are stored
            try {
                $videoFile->move(
                    $this->getParameter('video_directory'),
                    $newVideoFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            // Set the 'videoevt' property of the 'Evenement' entity with the new filename
            $evenement->setVideoevt($newVideoFilename);
        }

        // Persist and flush the 'Evenement' entity
        $entityManager->persist($evenement);
        $entityManager->flush();

        return $this->redirectToRoute('app_evenement_indexAdmin', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('evenement/new.html.twig', [
        'evenement' => $evenement,
        'form' => $form,
    ]);
}

    #[Route('/{idevt}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        
        ]);
    }

    #[Route('/{idevt}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_indexAdmin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{idevt}', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getIdevt(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evenement_indexAdmin', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/front/{idevt}', name: 'app_evenement_front', methods: ['GET'])]
public function front($idevt, EvenementRepository $evenementRepository): Response
{
    // Fetch the specific Evenement entity based on $idevt
    $evenement = $evenementRepository->find($idevt);

    if (!$evenement) {
        // Handle the case where the Evenement is not found, e.g., show an error page or redirect
        throw $this->createNotFoundException('The event does not exist');
    }

    return $this->render('evenement/front.html.twig', [
        'evenements' => $evenement,
    ]);
}
    #[Route('/evenement/search', name: 'evenement_search', methods: ['GET', 'POST'])]
public function searchAction(Request $request, EntityManagerInterface $em)
{
    $requestString = $request->get('query');
    $evenements =  $em->getRepository(Evenement::class)->findByTitreevt($requestString);

    if(!count($evenements)) {
        $result['evenements']['error'] = "Aucun événement trouvé";
    } else {
        $result['evenements'] = $this->getRealEntities($evenements);
    }

    return new JsonResponse($result);
}
#[Route('/evenement/filtre', name: 'evenement_filtre', methods: ['GET'])]
public function filtrerEvenements(Request $request, EntityManagerInterface $em, EvenementRepository $evenementRepository): Response
{
    $hdEvtString = $request->query->get('hdEvt');
    $hfEvtString = $request->query->get('hfEvt');

    $hdEvt = new \DateTimeImmutable($hdEvtString);
    $hfEvt = new \DateTimeImmutable($hfEvtString);

    // Assuming your Evenement entity has properties 'hdEvt' and 'hfEvt' of type DateTimeImmutable
    $evenement = $evenementRepository->findByTimeRange($hdEvt, $hfEvt);

    return $this->render('evenement/filtrer.html.twig', [
        'evenement' => $evenement,
        'hdEvt' => $hdEvt,
        'hfEvt' => $hfEvt,
    ]);
}

#[Route('/{idevt}/Cl', name: 'app_evenement_showCl', methods: ['GET'])]
public function showCl(Evenement $evenement, ReservationRepository $reservationRepository,EntityManagerInterface $em, $idevt): Response
{
    $reservation = $em->getRepository(Reservation::class)->find($idevt);
// $likesAndDislikes = $participationRepository->countLikesAndDislikes($evenement->getId_evenement());
$likesAndDislikes = $reservationRepository->countLikesAndDislikes($evenement->getIdevt());

return $this->render('evenement/showCl.html.twig', [
    'evenement' => $evenement,
    'reservation' => $reservation,
    'likes' => $likesAndDislikes['likes'],
    'dislikes' => $likesAndDislikes['dislikes'],
]);
}

public function getRealEntities($evenements)
{
    foreach ($evenements as $evenement) {
        $realEntities[] = [
            'idevt' => $evenement->getIdevt(),
            'titreevt' => $evenement->getTitreevt(),
            'addresseevt'=>$evenement->getAdresseevt(),
            'nomOrg'=>$evenement->getNomorg(),
            'hdEvt' => $evenement->getHdEvt() ? $evenement->getHdEvt()->format('H:i:s') : '',
            'hfEvt' => $evenement->getHfEvt() ? $evenement->getHfEvt()->format('H:i:s') : '',
            'descevt' => $evenement->getDescevt()
        ];
    }
    return $realEntities;
}

#[Route('/live-search', name: 'live_search')]
public function liveSearch(Request $request): Response
{
    $q = $request->query->get('q');
    $response = '...';

    return new Response($response);
}



#[Route('/{idevt}/{id}/like', name: 'event_like', methods: ['GET'])]
public function like(int $idevt, int $id, SessionInterface $session): Response
{
    $id=23;

    $entityManager = $this->getDoctrine()->getManager();
    $reservationRepository = $entityManager->getRepository(Reservation::class);
    $evenementRepository = $entityManager->getRepository(Evenement::class);

        
    $reservation = $reservationRepository->findOneBy(['id' => $id, 'idevt' => $idevt]);
    $evenement = $evenementRepository->findOneBy(['idevt' => $idevt]);
        
    if (!$reservation) {
        throw $this->createNotFoundException('Vous n\'avez pas encore participé ' . $idevt);
    }
        
    if ($evenement->getVote() == 1) {
        $this->addFlash('error', 'Vous avez déjà aimé cet événement');
    } else {
        if ($evenement->getVote() == 2) {
            $evenement->setVote(1);
            $this->addFlash('success', 'Merci d\'avoir changer votre vote et d\'avoir aimé cet événement');
        } else {
            $evenement->setVote(1);
            $this->addFlash('success', 'Merci d\'avoir aimé cet événement');
        }
        $entityManager->flush();
    }
        
    return $this->redirectToRoute('app_evenement_showCl', ['idevt' => $idevt]);
}

#[Route('/{idevt}/{id}/dislike', name: 'event_dislike', methods: ['GET'])]
public function dislike(int $idevt, int $id, SessionInterface $session): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $reservationRepository = $entityManager->getRepository(Reservation::class);
    $evenementRepository = $entityManager->getRepository(Evenement::class);

    $reservation = $reservationRepository->findOneBy(['id' => $id, 'idevt' => $idevt]);
    $evenement = $evenementRepository->findOneBy(['idevt' => $idevt]);

    if (!$reservation) {
        throw $this->createNotFoundException('Vous n\'avez pas encore participé à ' . $idevt);
    }

    if ($evenement->getVote() == 2) {
        $this->addFlash('error', 'Vous avez déjà désaimé cet événement');
    } else {
        if ($evenement->getVote() == 1) {
            $evenement->setVote(2);
            $this->addFlash('success', 'Merci d\'avoir changé votre vote et d\'avoir désaimé cet événement');
        } else {
            $evenement->setVote(2);
            $this->addFlash('success', 'Merci d\'avoir désaimé cet événement');
        }
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_evenement_showCl', ['idevt' => $idevt]);
}

}
