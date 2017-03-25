<?php

namespace Cilex\Command;

use App\Service\ScreenshotService;
use Cilex\Provider\Console\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DownloadScreenshot extends Command
{
    use LockableTrait;

    protected function configure()
    {
        $this->setName('download:screenshot');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->lock()) {
            $output->writeln('Task is already running');
            return;
        }

        /** @var ScreenshotService $screenshotService */
        $screenshotService = $this->getContainer()->get('screenshot');

        $screenshotService->download();

        $this->release();
    }
}