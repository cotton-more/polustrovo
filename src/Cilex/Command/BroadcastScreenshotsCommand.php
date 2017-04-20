<?php

namespace Cilex\Command;

use App\Service\Broadcast\BroadcastService;
use App\Service\Notifier\PushbulletNotifier;
use Cilex\Provider\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BroadcastScreenshotsCommand extends Command
{
    public function configure()
    {
        $this->setName('broadcast:screenshots');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var BroadcastService $service */
        $service = $this->getContainer()->get('broadcast');

        $service->broadcast();
    }
}