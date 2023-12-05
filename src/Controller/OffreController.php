<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\Panier;
use App\Form\OffreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\WeatherService;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route('/offre')]
class OffreController extends AbstractController
{
    private $doctrine;
    #[Route('/', name: 'app_offre_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $offres = $entityManager
            ->getRepository(Offre::class)
            ->findAll();

        return $this->render('offre/index.html.twig', [
            'offres' => $offres,
        ]);
    }
    #[Route('/weather/{city}', name: 'app_offre_weather')]
    public function weather(string $city): Response
    {
        $apiKey = 'fdb2c1ec0c425b458426cf34f6475082'; 
        $httpClient = HttpClient::create();

        $url = sprintf('https://api.openweathermap.org/data/2.5/weather?q=%s&appid=%s', $city, $apiKey);

        $response = $httpClient->request('GET', $url);

        $weatherData = $response->toArray();

        return $this->render('weather/weather.html.twig', [
            'city' => $city,
            'weatherData' => $weatherData,
        ]);
    }



    #[Route('/client', name: 'app_offre_index_client', methods: ['GET'])]
    public function index_client(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $entityManager->getRepository(Offre::class)->createQueryBuilder('o')->getQuery();

        $offres = $entityManager
            ->getRepository(Offre::class)
            ->findAll();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('offre/index_client.html.twig', [
            'pagination' => $pagination, 'offres' => $offres
        ]);
    }


    #[Route('/new', name: 'app_offre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        $imageDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads/images';

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageoffre')->getData();
            if ($imageFile) {

                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move($imageDirectory, $newFilename);

                $offre->setImageOffre($newFilename);
            }
            $entityManager->persist($offre);
            $entityManager->flush();

            return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offre/new.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }
    // #[Route('/{nomoffre}/add-to-cart', name: 'app_offre_add_to_cart', methods: ['GET'])]
    // public function addToCartByNomOffre($nomoffre, SessionInterface $session, EntityManagerInterface $entityManager): Response
    // {
    //     try {
    //         // Recherchez l'offre dans la base de données par le nom
    //         $offre = $this->getDoctrine()->getRepository(Offre::class)->findOneBy(['nomoffre' => $nomoffre]);

    //         if (!$offre) {
    //             // Gérer le cas où l'offre n'est pas trouvée
    //             return $this->json(['error' => 'Offre non trouvée.'], 404);
    //         }

    //         // Démarrez la transaction
    //         $entityManager->beginTransaction();

    //         $panier = $session->get('panier', []);

    //         // Ajouter l'offre au panier
    //         $panier[$offre->getIdOffre()] = [
    //             'idoffre' => $offre->getIdOffre(),
    //             'nomoffre' => $offre->getNomOffre(),
    //             // Ajoutez d'autres champs d'offre au besoin
    //         ];

    //         // Mettre à jour le panier dans la session
    //         $session->set('panier', $panier);

    //         // Flush des changements en base de données
    //         $entityManager->persist($offre);
    //         $entityManager->flush();

    //         // Commit de la transaction
    //         $entityManager->commit();

    //         // Retournez une réponse JSON (optionnelle)
    //         return $this->json(['message' => 'Offre ajoutée au panier.']);
    //     } catch (\Exception $e) {
    //         // Rollback de la transaction en cas d'erreur
    //         $entityManager->rollback();

    //         // Gestion de l'erreur (log, affichage, etc.)
    //         return $this->json(['error' => 'Une erreur est survenue.']);
    //     }
    // }

    // #[Route('/{id}/add-to-carts', name: 'app_offre_add_to_carts', methods: ['GET'])]
    // public function addToCartById(Offre $offre, EntityManagerInterface $entityManager, $id): Response
    // {
    //     try {
    //         // Démarrez la transaction
    //         $entityManager->beginTransaction();

    //         // Créez une nouvelle instance de Panier et associez l'offre
    //         $panier = new Panier();
    //         $panier->setIdOffre($offre);
    //         $panier->setId($id);

    //         // Ajoutez d'autres champs d'offre au besoin

    //         // Persistez le panier
    //         $entityManager->persist($panier);

    //         // Flush des changements en base de données
    //         $entityManager->flush();

    //         // Commit de la transaction
    //         $entityManager->commit();

    //         // Redirigez vers une page de confirmation ou ailleurs
    //         return $this->redirectToRoute('app_offre_index_client');
    //     } catch (\Exception $e) {
    //         // Rollback de la transaction en cas d'erreur
    //         $entityManager->rollback();

    //         // Gestion de l'erreur (log, affichage, etc.)
    //         // Redirigez vers une page d'erreur ou faites ce qui est approprié dans votre application
    //         return $this->redirectToRoute('app_offre_index_client');
    //     }
    // }

    #[Route('/{idoffre}', name: 'app_offre_show', methods: ['GET'])]
    public function show(Offre $offre): Response
    {
        return $this->render('offre/show.html.twig', [
            'offre' => $offre,
        ]);
    }

    #[Route('/{idoffre}/edit', name: 'app_offre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    #[Route('/{idoffre}', name: 'app_offre_delete', methods: ['POST'])]
    public function delete(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $offre->getIdoffre(), $request->request->get('_token'))) {
            $entityManager->remove($offre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
    }








    #[Route('/offre/{id}/pdf', name: 'offre_pdf')]
    public function generatePDF(Offre $offre): Response
    {
        $html = $this->renderView('offre/offre_pdf.html.twig', [
            'offre' => $offre,
        ]);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }
    #[Route("/search", name: "search")]
    public function search(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $query = $request->query->get('search');
            $results = $entityManager->getRepository(Offre::class)->searchByName($query);

            $formattedResults = [];
            foreach ($results as $result) {
                $formattedResults[] = [
                    'idoffre' => $result->getIdoffre(),
                    'nomoffre' => $result->getNomoffre(),
                    'description' => $result->getDescription(),
                    'datedebut' => $result->getDatedebut(),
                    'datefin' => $result->getDatefin(),
                    'typeoffre' => $result->getTypeoffre(),
                    'imageoffre' => $result->getImageoffre(),
                    'valeuroffre' => $result->getValeuroffre(),
                    'status' => $result->getStatus(),

                ];
            }

            return new JsonResponse(['results' => $formattedResults]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
