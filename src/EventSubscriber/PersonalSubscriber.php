<?php

namespace App\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PersonalSubscriber implements EventSubscriberInterface
{
    public ?RouterInterface $router = null;
    public ?Security $security = null;
    public ?EntityManagerInterface $entityManager = null;
    public ?CacheInterface $cache = null;

    public function __construct(RouterInterface $router, Security $security, CacheInterface $cache)
    {
        $this->router = $router;
        $this->security = $security;
        $this->cache = $cache;
    }

    public function onLoginSuccessEvent(LoginSuccessEvent $event)
    {
        // Enregistrer dans le cache le mail du dernier utilisateur connecté
        $personal = $event->getPassport()->getUser();

        // dd($personal->getEmail());
        // Exemple de logique de mise en cache
        $value = $this->cache->get('lastIdentifier', function (ItemInterface $item) use ($personal): string {
            // dd($personal);

            $item->expiresAfter(null); // délai d'expiration indéfini 

            $data = $personal->getEmail();;

            return $data;
        });

        //dd($value);

        /* Redirection de l'utilisateur si première connexion */

        //$request = $event->getRequest();
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
