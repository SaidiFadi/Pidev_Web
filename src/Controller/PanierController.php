<?php

namespace App\Controller;

use App\Entity\Offre;

use App\Entity\Panier;
use App\Entity\Personne;
use App\Form\PanierType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Form\PanierAfficheType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Service\EmailService;
use Psr\Log\LoggerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Response;


#[Route('/panier')]
class PanierController extends AbstractController
{
    private ManagerRegistry $doctrine;
    private EmailService $emailService;

    private $mailer;

    public function __construct(ManagerRegistry $doctrine, EmailService $emailService, MailerInterface $mailer)
    {
        $this->doctrine = $doctrine;
        $this->emailService = $emailService;
        $this->mailer = $mailer;
    }



    private static $panierListe = [];
    #[Route('/', name: 'app_panier_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $paniers = $entityManager
            ->getRepository(Panier::class)
            ->findAll();

        return $this->render('panier/index.html.twig', [
            'paniers' => $paniers,
        ]);
    }

    #[Route('/supprimer-offre/{nomOffre}', name: 'supprimer_offre_panier')]
    public function supprimerOffrePanier(string $nomOffre, EntityManagerInterface $entityManager): Response
    {
        // Dump the contents of the Panier repository
        $panierRepository = $entityManager->getRepository(Panier::class);
        $paniers = $panierRepository->findAll();
        dump($paniers);

        // Rechercher l'offre dans le panier par le nom
        $nomOffre = trim($nomOffre);
        dump($nomOffre);

        // Use the custom repository method
        $panier = $panierRepository->findByNomOffre($nomOffre);
        dump($panier);

        if (!$panier) {
            throw $this->createNotFoundException('L\'offre avec le nom ' . $nomOffre . ' n\'a pas été trouvée dans le panier.');
        }

        // Supprimer l'offre du panier
        $entityManager->remove($panier);
        $entityManager->flush();

        // Add a flash message to inform about the deletion
        $this->addFlash('success', 'L\'offre a été supprimée du panier.');

        // Redirect to the panier page
        return $this->redirectToRoute('afficher_panier');
    }







    private function calculerPourcentagesOffres(array $paniers): array
    {
        $typesOffre = [];

        // Iterate over paniers and count the number of each type of offer
        foreach ($paniers as $panier) {
            $typeOffre = $panier->getIdOffre()->getTypeoffre();

            if (array_key_exists($typeOffre, $typesOffre)) {
                $typesOffre[$typeOffre]++;
            } else {
                $typesOffre[$typeOffre] = 1;
            }
        }

        // Calculate the percentages
        $totalPaniers = count($paniers);
        $pourcentages = [];

        foreach ($typesOffre as $type => $nombre) {
            $pourcentage = ($nombre / $totalPaniers) * 100;
            $pourcentages[$type] = $pourcentage;
        }

        return $pourcentages;
    }
    #[Route('/afficher-pourcentages-offres', name: 'afficher_pourcentages_offres')]
    public function afficherPourcentagesOffres(EntityManagerInterface $entityManager): Response
    {
        // Récupérez les paniers depuis la base de données
        $paniers = $entityManager->getRepository(Panier::class)->findAll();

        // Utilisez la fonction pour calculer les pourcentages
        $pourcentagesOffres = $this->calculerPourcentagesOffres($paniers);
        $panier = new Panier;
        // Passez la variable 'panier' au template
        return $this->render('panier/afficher_pourcentages_offres.html.twig', [
            'pourcentagesOffres' => $pourcentagesOffres,
            'panier' => $panier, // Assurez-vous de définir la variable $panier avec les données nécessaires
        ]);
    }

    #[Route('/afficher-pourcentages-offres2', name: 'afficher_pourcentages_offres2')]
    public function afficherPourcentagesOffres2(EntityManagerInterface $entityManager): Response
    {
        // Récupérez les paniers depuis la base de données
        $paniers = $entityManager->getRepository(Panier::class)->findAll();

        // Utilisez la fonction pour calculer les pourcentages
        $pourcentagesOffres = $this->calculerPourcentagesOffres($paniers);
        $panier = new Panier;
        // Passez la variable 'panier' au template
        return $this->render('panier/afficher_pourcentages_offres_base.html.twig', [
            'pourcentagesOffres' => $pourcentagesOffres,
            'panier' => $panier, // Assurez-vous de définir la variable $panier avec les données nécessaires
        ]);
    }
    #[Route('/new/{offreId}', name: 'app_panier_new', methods: ['GET', 'POST'])]
    public function new(EntityManagerInterface $entityManager, int $offreId): Response
    {
        // Retrieve an existing Personne entity from the database (you may need to adjust this based on your logic)
        $existingPersonne = $entityManager->getRepository(Personne::class)->find(23);

        if (!$existingPersonne) {
            // Handle the case where the Personne entity with id 23 is not found.
            // You might want to redirect to an error page or take appropriate action.
        }

        // Retrieve the selected Offre entity based on the ID from the URL
        $selectedOffre = $entityManager->getRepository(Offre::class)->find($offreId);

        if (!$selectedOffre) {
            // Handle the case where the selected Offre entity is not found.
            // You might want to redirect to an error page or take appropriate action.
        }

        $existingOffre = $entityManager->getRepository(Panier::class)->findOfferInPanierByPersonne(
            $selectedOffre->getNomoffre(),
            $existingPersonne->getId()
        );

        if ($existingOffre) {
            $this->addFlash('warning', ' offre deja  existe dans le panier.');
            return $this->redirectToRoute('app_offre_index_client');
        }
        if ($selectedOffre->getStatus() === 'Expirée') {
            $this->addFlash('warning', 'L\'offre est expirée et ne peut pas être ajoutée au panier.');

            return $this->redirectToRoute('app_offre_index_client');
        }

        $panier = new Panier();
        $panier->setId($existingPersonne);
        $panier->setIduser(0);
        // Add the selected Offre to the Panier
        $panier->setIdOffre($selectedOffre);

        // Set the datePanier property to the current date and time
        $panier->setDatePanier(new \DateTime());

        // Persist the Panier directly
        $entityManager->persist($panier);
        $entityManager->flush();

        // Redirect to the page showing the panier
        return $this->redirectToRoute('afficher_panier', ['id' => $panier->getIdPanier()]);
    }

    #[Route('/afficher-panier', name: 'afficher_panier')]
    public function afficherPanier(EntityManagerInterface $entityManager): Response
    {
        // Retrieve panier information from the database (adjust the logic based on your needs)
        $panierListe = $entityManager->getRepository(Panier::class)->findBy(['id' => 23]);

        return $this->render('panier/afficher_panier.html.twig', [
            'panierListe' => $panierListe,
        ]);
    }
    #[Route('/confirmer-panier', name: 'confirmer_panier')]
    public function sendEmail(EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {


        // Retrieve panier information from the database (adjust the logic based on your needs)
        $panierListe = $entityManager->getRepository(Panier::class)->findAll();

        // Ensure that the panier is not empty before sending the email
        if (!empty($panierListe)) {
            // Assuming you have a Panier entity with appropriate fields
            $panier = $panierListe[0]; // Assuming there's at least one panier
            // Calculate the total
            $total = 0;
            foreach ($panierListe as $item) {
                $total += $item->getIdOffre()->getValeurOffre();
            }

            // Customize the email content based on panier information
            $email = (new Email())
                ->from('ithmayni@gmail.com')
                ->to($panier->getId()->getEmail()) // Assuming you have an email field in Panier entity
                ->subject('Confirmation de Panier')
                ->html(
                    $this->renderView('emails/confirmation_panier.html.twig', [
                        'panierListe' => $panierListe, // Pass the panierListe variable to the template
                        'total' => $total,
                        'panier' => $panier,
                    ])
                );

            // Send the email
            $mailer->send($email);

            return new Response('Email sent!');
        } else {
            return new Response('Le panier est vide, aucun e-mail n\'a été envoyé.');
        }
    }


    private function calculerTotalPanier(Personne $client): float
    {
        // Implémentez la logique pour calculer le total du panier pour le client donné
        $panierListe = $this->getPanierListeForEmail(); // Utilisez la méthode pour récupérer la liste du panier
        $total = 0;

        foreach ($panierListe as $panier) {
            $total += $panier->getIdOffre()->getValeurOffre();
        }

        return $total;
    }


    #[Route('/{idpanier}', name: 'app_panier_show', methods: ['GET'])]
    public function show(Panier $panier): Response
    {
        return $this->render('panier/show.html.twig', [
            'panier' => $panier,
        ]);
    }

    #[Route('/{idpanier}/edit', name: 'app_panier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('panier/edit.html.twig', [
            'panier' => $panier,
            'form' => $form,
        ]);
    }

    #[Route('/{idPanier}', name: 'app_panier_delete', methods: ['POST'])]
    public function delete(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $panier->getIdpanier(), $request->request->get('_token'))) {
            $entityManager->remove($panier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
    }


    private function getPanierListeForEmail(): array
    {
        // Implémentez la logique pour récupérer les détails du panier à inclure dans l'e-mail
        return $this->doctrine->getRepository(Panier::class)->findAll();
    }
    #[Route('/send-test-email', name: 'app_send_test_email', methods: ['GET'])]
    public function sendTestEmail(EntityManagerInterface $entityManager): Response
    {
        // Récupérer la personne ayant l'ID 23
        $existingPersonne = $entityManager->getRepository(Personne::class)->find(23);
        if (!$existingPersonne) {
            // Gérer le cas où la personne avec l'ID 23 n'est pas trouvée
            // Vous pouvez rediriger vers une page d'erreur ou prendre une autre action appropriée
        }

        // Appeler la fonction pour envoyer un e-mail
        $this->sendTestEmailInternal($existingPersonne);

        // Vous pouvez également ajouter un message Flash ou tout autre traitement après l'envoi de l'e-mail
        $this->addFlash('success', 'E-mail de test envoyé avec succès.');

        return $this->redirectToRoute('app_panier_index');
    }

    private function sendTestEmailInternal(Personne $personne): void
    {
        $recipient = $personne->getEmail();
        $subject = 'Test Email';
        $body = 'Ceci est un e-mail de test envoyé depuis Symfony.';

        // Envoyer l'e-mail en utilisant le service EmailService
        $this->emailService->sendEmail($recipient, $subject, $body);
    }
}
