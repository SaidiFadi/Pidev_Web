<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Form\PersonneType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Form\registerType;
// use Swift_Mailer;
// use Swift_Message;
// use Swift_SmtpTransport;

#[Route('/personne')]
class PersonneController extends AbstractController
{
    private $passwordEncoder;

    #[Route('/', name: 'app_personne_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $personnes = $entityManager
            ->getRepository(Personne::class)
            ->findAll();

        return $this->render('personne/index.html.twig', [
            'personnes' => $personnes,
        ]);
    }
    //

    #[Route('/front', name: 'front_personne', methods: ['GET'])]
    public function frontperso(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        return $this->render('personne/front.html.twig');
    }

    #[Route('/register', name: 'register', methods:['GET', 'POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher,EntityManagerInterface $entityManager): Response
    {
        
        $user = new Personne();
        $form = $this->createForm(registerType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            /** @var UploadedFile $file */
            $file = $form->get('pprofile')->getData();

            // If a file was uploaded
            if ($file) {
                $filename = uniqid() . '.' . $file->guessExtension();

                // Move the file to the directory where brochures are stored
                $file->move(
                    'userImages',
                    $filename
                );

                // Update the 'image' property to store the image file name
                // instead of its contents
                $user->setPprofile($filename);
            }
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );


            
            //$logger->debug('The signed URL is: ' . $signatureComponents->getSignedUrl());
            //dump($logger);
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('login');
        }
        return $this->render('personne/register.html.twig'
        , [
            'personne' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/login', name: 'login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        
        return $this->render('personne/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
        
    }

    // #[Route('/forgot-password', name: 'app_forgot_password', methods: ['GET', 'POST'])]
    // public function forgotPassword(Request $request, \Swift_Mailer $mailer): Response
    // {
    // // Handle the form submission and send a password reset email
    // // Example: Send a password reset email
    // if ($request->isMethod('POST')) {
    //     $email = $request->request->get('email');
    //     // Logic to generate a reset token and send an email

    //     // Example using Swift Mailer
    //     $message = (new \Swift_Message('Password Reset'))
    //         ->setFrom('noreply@example.com')
    //         ->setTo($email)
    //         ->setBody(
    //             $this->renderView(
    //                 'emails/reset_password.html.twig',
    //                 ['token' => $resetToken]
    //             ),
    //             'text/html'
    //         );

    //     $mailer->send($message);

    //     // Flash message or redirect as needed
    //     $this->addFlash('success', 'Check your email for the password reset link.');
    //     return $this->redirectToRoute('app_login');
    // }

    // return $this->render('personne/forgotPassword.html.twig');
    // }


    #[Route('/new', name: 'app_personne_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager , UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $personne = new Personne();
        $form = $this->createForm(PersonneType::class, $personne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             /** @var UploadedFile $file */
             $file = $form->get('pprofile')->getData();

             // If a file was uploaded
             if ($file) {
                 $filename = uniqid() . '.' . $file->guessExtension();
 
                 // Move the file to the directory where brochures are stored
                 $file->move(
                     'userImages',
                     $filename
                 );
 
                 // Update the 'image' property to store the image file name
                 // instead of its contents
                 $personne->setPprofile($filename);
             }
            // Hash the plain password before persisting the entity
            $hashedPassword = $passwordEncoder->encodePassword($personne, $personne->getPassword());
            $personne->setPassword($hashedPassword);
            $entityManager->persist($personne);
            $entityManager->flush();

            return $this->redirectToRoute('app_personne_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('personne/new.html.twig', [
            'personne' => $personne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_personne_show', methods: ['GET'])]
    public function show(Personne $personne): Response
    {
        return $this->render('personne/show.html.twig', [
            'personne' => $personne,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_personne_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Personne $personne, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PersonneType::class, $personne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_personne_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('personne/edit.html.twig', [
            'personne' => $personne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_personne_delete', methods: ['POST'])]
    public function delete(Request $request, Personne $personne, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$personne->getId(), $request->request->get('_token'))) {
            $entityManager->remove($personne);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_personne_index', [], Response::HTTP_SEE_OTHER);
    }
}
