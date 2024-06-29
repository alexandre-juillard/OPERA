<?php

namespace App\Scheduler\Handler;

use App\Repository\ConnectedPersonalRepository;
use App\Repository\PersonalRepository;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Mercure\HubInterface;
use App\Scheduler\Message\ResetPasswordNotification;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ResetPasswordNotificationHandler
{

    public function __construct(private HubInterface $hub, private ConnectedPersonalRepository $connectedPersonalRepository)
    {
    }

    public function __invoke(ResetPasswordNotification $message)
    {

        // dd($this->connectedPersonalRepository->findAll());

        //dd($this->security->getUser());



        dump("hello");
        $update = new Update(
            'notifPasswordReset',
            'Veuillez modifier votre mot de passe'
        );
        $this->hub->publish($update);

        //     dd('ok');

        //     $user = $this->security->getUser(); // L"utilisateur connecté n'est pas reconnu et je ne sais pas pourquoi
        //     dd($user);

        //     if ($user) {
        //         $intervals = ['P0D', 'P83D', 'P85D', 'P87D', 'P90D'];
        //         $lastUpdatedPassword = $user->getLastUpdatedPassword();
        //         $today = new DateTime();

        //         foreach ($intervals as $interval) {
        //             $dateToCheck = clone $lastUpdatedPassword;
        //             $dateToCheck->add(new DateInterval($interval));
        //             if ($dateToCheck->format('Y-m-d') === $today->format('Y-m-d')) {
        //                 $update = new Update(
        //                     'notifPasswordReset',
        //                     'Veuillez modifier votre mot de passe'
        //                 );

        //                 $this->hub->publish($update);
        //                 //  return new Response('Notif envoyé!');
        //             }
        //         }
        //     } else {
        //         throw new \ErrorException("Aucun utilisateur connecté");
        //     }
    }
}
