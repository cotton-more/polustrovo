<?php

namespace Cilex\Command;

use App\Service\Telegram\TelegramService;
use Cilex\Provider\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TelegramSendPhotoCommand extends Command
{
    public function configure()
    {
        $this->setName('telegram:send:photo');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var TelegramService $service */
        $service = $this->getContainer()->get('telegram');

        $service->sendPhoto();
    }
}