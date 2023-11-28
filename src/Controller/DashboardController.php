<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EvenementRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
public function index(EvenementRepository $evenementRepository): Response
{
    $topEventsByParticipants = $evenementRepository->topEventsByParticipants();
        

        return $this->render('siwar/stat.html.twig', [
            'topEventsByParticipants' => $topEventsByParticipants,
            
        ]);
    }

    #[Route('/dashboard/topVote', name: 'app_topVote')]
    public function topEventsByLikes(EvenementRepository $evenementRepository): Response
    {
        $topEventsByLikes = $evenementRepository->topEventsByLikes();
    
        return $this->render('siwar/stat.html.twig', [
            'topEventsByLikes' => $topEventsByLikes,
        ]);
    }

}

    

