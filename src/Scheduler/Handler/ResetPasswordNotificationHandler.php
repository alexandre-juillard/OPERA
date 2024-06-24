<?php

namespace App\Scheduler\Handler;

use Symfony\Bundle\SecurityBundle\Security;
use App\Scheduler\Message\ResetPasswordNotification;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ResetPasswordNotificationHandler
{
    public ?Security $security;

    public function __invoke(ResetPasswordNotification $message)
    {
        dump('hello');
    }
}
