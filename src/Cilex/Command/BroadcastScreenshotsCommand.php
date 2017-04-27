<?php

namespace Cilex\Command;

use App\Service\Broadcast\BroadcastService;
use Cilex\Provider\Console\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BroadcastScreenshotsCommand extends Command
{
    use LockableTrait;

    public function configure()
    {
        $this->setName('broadcast:screenshots');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->lock()) {
            $output->writeln($this->getName(). ' is already running');
            return;
        }

        /** @var BroadcastService $service */
        $service = $this->getContainer()->get('broadcast');

        $service->broadcast();
    }
}