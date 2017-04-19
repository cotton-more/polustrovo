<?php

namespace Cilex\Command;

use App\Service\Notifier\PushbulletNotifier;
use Cilex\Provider\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PushbulletSendPhotoCommand extends Command
{
    public function configure()
    {
        $this->setName('pushbullet:send:photo');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var PushbulletNotifier $service */
        $service = $this->getContainer()->get('pushbullet.notifier');

        $service->sendPhoto();
    }
}