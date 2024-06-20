<?php

namespace App\Scheduler\Handler;

use App\Scheduler\Message\Test;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class TestHandler
{
    public function __construct(private readonly KernelInterface $kernel)
    {
    }

    public function __invoke(Test $message): void
    {
        $path = $this->kernel->getProjectDir() . '/var/log/cron_test.log';

        file_put_contents($path, Carbon::now()->format('Y-m-d H:i:s') . "\n", FILE_APPEND);
    }
}
