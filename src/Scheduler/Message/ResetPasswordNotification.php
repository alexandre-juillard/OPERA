<?php

namespace App\Scheduler\Message;


class ResetPasswordNotification
{
    private ?int $personalId = null;

    public function __construct(int $personalId)
    {
        $this->personalId = $personalId;
    }



    /**
     * Get the value of userId
     */
    public function getPersonalId()
    {
        return $this->personalId;
    }
}
