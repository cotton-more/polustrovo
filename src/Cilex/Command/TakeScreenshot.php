<?php

namespace Cilex\Command;

use Cilex\Provider\Console\Command;
use App\Service\ScreenshotService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TakeScreenshot extends Command
{
    protected function configure()
    {
        $this->setName('take:screenshot');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var ScreenshotService $screenshotService */
        $screenshotService = $this->getContainer()->offsetGet('screenshot');

        $screenshotService->take();
    }
}