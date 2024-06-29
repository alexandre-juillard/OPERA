<?php

namespace App\EventSubscriber;

use App\Entity\ConnectedPersonal;
use App\Repository\ConnectedPersonalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PersonalSubscriber implements EventSubscriberInterface
{
    public ?RouterInterface $router = null;
    public ?Security $security = null;
    public ?EntityManagerInterface $entityManager = null;
    public ?ConnectedPersonalRepository $connectedRepository = null;

    public function __construct(RouterInterface $router, Security $security, EntityManagerInterface $entityManager, ConnectedPersonalRepository $connecteRepository)
    {
        $this->router = $router;
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->connectedRepository = $connecteRepository;
    }

    public function onLoginSuccessEvent(LoginSuccessEvent $event)
    {


        /* Ajout de l'utilisateur connecté  la table connected_user */
        /** @var \Personal $personal */
        $personal = $event->getPassport()->getUser();

        $email = $personal->getEmail();

        $connectedPersonnal = $this->connectedRepository->findBy(
            ['email' => $email]
        );

        // dd($connectedPersonnal);

        if (!$connectedPersonnal) {
            $connectedPersonnal = new ConnectedPersonal();
            $connectedPersonnal->setEmail($email);

            $this->entityManager->persist($connectedPersonnal);
            $this->entityManager->flush();
        }


        /* Redirection de l'utilisateur si première connexion */

        //$request = $event->getRequest();
        // dd($user->getFirstConnexion());
        if ($personal->getFirstConnexion() == null) {
            // Redirige vers une nouvelle URL
            $response = new RedirectResponse($this->router->generate('change_password_submit'));
            $event->setResponse($response); // Modifie la réponse de l'événement     
        }
    }


    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => ['onLoginSuccessEvent', 1000],
        ];
    }
}
