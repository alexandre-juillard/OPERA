<?php

namespace App\EventSubscriber;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PersonalSubscriber implements EventSubscriberInterface
{
    public ?RouterInterface $router = null;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }
    public function onLoginSuccessEvent(LoginSuccessEvent $event)
    {
        //$request = $event->getRequest();
        /** @var \User $user */
        $user = $event->getPassport()->getUser();
        // dd($user->getFirstConnexion());
        if ($user->getFirstConnexion() == null) {
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
