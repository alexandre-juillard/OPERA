<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordResetRequestFormType;
use App\Form\PasswordResetType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route("/login", name: "app_login")]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route("/logout", name: "app_logout", methods: ["GET"])]
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
    
    #[Route('/forget-password', name: 'app_forget_password', methods: ['GET', 'POST'])]
    public function forgetPassword(Request $request, UserRepository $userRepository, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PasswordResetRequestFormType::class);
        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $email = $form->get('email')->getData();
        $user = $userRepository->findOneByEmail($email);

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $user->setResetToken($token);
            $entityManager->persist($user);
            $entityManager->flush();

            $resetUrl = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

            $email = (new Email())
                ->from('noreply@example.com')
                ->to($user->getEmail())
                ->subject('Réinitialisation de votre mot de passe')
                ->html("Pour réinitialiser votre mot de passe, veuillez cliquer sur le lien suivant : <a href='$resetUrl'>$resetUrl</a>");

            $mailer->send($email);

            $this->addFlash('success', 'Un email de réinitialisation de mot de passe a été envoyé.');
            return $this->redirectToRoute('app_forget_password');
        } else {
            $this->addFlash('danger', 'Aucun utilisateur trouvé avec cet email.');
        }
    }

    return $this->render('security/forget_password.html.twig', [
        'resetRequestForm' => $form->createView(),
    ]);
}
    public function resetPassword(Request $request, $token, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->findOneBy(['resetToken' => $token]);

        if (!$user) {
            $this->addFlash('danger', 'Token invalide');
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(PasswordResetType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('plainPassword')->getData();
            $user->setResetToken(null); // Effacer le token de réinitialisation
            $user->setPassword($passwordHasher->hashPassword($user, $newPassword));

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre mot de passe a été réinitialisé avec succès.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }
}
