<?php

namespace App\Controller;

use App\Entity\Search;
use App\Form\SearchType;
use App\Entity\Reclamation;
use App\Entity\Commaintaire;
use App\Form\CommentaireType;
use App\Form\ReclamationType;
use Symfony\Component\HttpFoundation\Response;

use App\Repository\RelamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Dompdf\Dompdf;
use Dompdf\Options;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/reclamation')]
class ReclamationController extends AbstractController
{

    
#[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
public function index(EntityManagerInterface $entityManager ,Request $request): Response
{
    

    

    $articles = [];

 
            $articles = $this->getDoctrine()->getRepository(Reclamation::class)
                ->findAll();


    return $this->render('reclamtion/details.html.twig', [
        
        'articles' => $articles
    ]);




    

   
}




#[Route('/stat', name: 'app_reclamationyy')]
public function stat(RelamationRepository $reclaR)
{
    $reclamations = $this->getDoctrine()->getRepository(Reclamation::class)->findAll();

    $reclamationData = [];

    foreach ($reclamations as $reclamation) {
        $etat = $reclamation->getEtat();

        if (!isset($reclamationData[$etat])) {
            $reclamationData[$etat] = 1;
        } else {
            $reclamationData[$etat]++;
        }
    }

    $ReclamationNom = array_keys($reclamationData);
    $ReclamationCount = array_values($reclamationData);

    $ReclamationColor = ["#FF0000", "#00FF00", "#CFEE45"];

    return $this->render('reclamtion/stat.html.twig', [
        'ReclamationNom' => json_encode($ReclamationNom),
        'ReclamationColor' => json_encode($ReclamationColor),
        'ReclamationCount' => json_encode($ReclamationCount),
    ]);
}











  







    
    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $commentaire = new Commaintaire();
      
        $commentaire->setMessage($evenement->getMessage());

            $evenement->setEtat('non traité');
           
            $entityManager->persist($evenement);

            $entityManager->persist($commentaire);


            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamtion/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);


    }





    #[Route('/admin', name: 'apptest', methods: ['GET', 'POST'])]
    public function admin(EntityManagerInterface $entityManager ,Request $request ): Response
    {
        

        $search = new Search();
        $form = $this->createForm(SearchType::class, $search );
        $form->handleRequest($request);
    
        $articles = [];
    
        if ($form->isSubmitted() && $form->isValid()) {
            $etat = $search ->getEtat();
    
            if ($etat !== "") {
                $articles = $this->getDoctrine()->getRepository(Reclamation::class)
                    ->findBy(['etat'=> $etat]);
            } else {
                $articles = $this->getDoctrine()->getRepository(Reclamation::class)
                    ->findAll();

            }
        }
    
        return $this->render('reclamtion/adminR.twig', [
            'form' => $form->createView(),
            'articles' => $articles
        ]);


    }
    

    #[Route('/changer/{id}', name: 'changer_etat_reclamation', methods: ['GET', 'POST'])]
    public function changerEtatReclamation(Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {


        $articles = $entityManager
        ->getRepository(Reclamation::class)
        ->findAll();
      
        $reclamation->setEtat('traitée');
        $entityManager->flush();


       return $this->redirectToRoute('app_reclamation_index');
    
        return $this->render('reclamtion/adminR.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/pdf', name: 'pdf', methods: ['GET'])]
    public function pdf(EntityManagerInterface $entityManager ,Request $request): Response
    {

          $options = new Options();
    $options->set('defaultFont', 'Arial');
    $dompdf = new Dompdf($options);



    $articles = $this->getDoctrine()->getRepository(Reclamation::class)->findAll();

    $html =$this->renderView('reclamtion/pdf.html.twig', [
        
        'articles' => $articles
    ]);



    $dompdf->loadHtml($html);
 

     $dompdf->setPaper('A4', 'portrait');



     $dompdf->render();



    $dompdf->stream("mypdf.pdf",["Attachnent" => true]);


    }

  
























    

    #[Route('/refuser/{id}', name: 'changer_etat_refuser', methods: ['GET', 'POST'])]
    public function rufuser(Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {


        $articles = $entityManager
        ->getRepository(Reclamation::class)
        ->findAll();
      
        $reclamation->setEtat('refuser');
        $entityManager->flush();

       return $this->redirectToRoute('app_reclamation_index');
    
        return $this->render('reclamtion/adminR.twig', [
            'articles' => $articles,
        ]);
    }




    #[Route('/newCommaintaire', name: 'app_Commentaire_new', methods: ['GET', 'POST'])]
    public function commentaire(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commentaire = new Commaintaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $forbiddenWords = ['BAD', 'RAYENE', 'test'];
    
            $message = $commentaire->getMessage();
            foreach ($forbiddenWords as $forbiddenWord) {
                if (stripos($message, $forbiddenWord) !== false) {
                    $this->addFlash('danger', 'Le commentaire contient un mot interdit.');
                    return $this->redirectToRoute('app_Commentaire_new');
                }
            }
    
            $entityManager->persist($commentaire);
            $entityManager->flush();
    
            $this->addFlash('success', 'Le commentaire a été ajouté avec succès.');
            return $this->redirectToRoute('app_Commentaire_new');
        }
    
        return $this->renderForm('commentaire/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }
    



}



