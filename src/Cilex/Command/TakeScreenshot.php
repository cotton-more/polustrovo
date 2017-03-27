<?php

namespace Cilex\Command;

use Cilex\Provider\Console\Command;
use App\Service\ScreenshotService;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TakeScreenshot extends Command
{
    protected function configure()
    {
        $this->setName('take:screenshot');
        $this->setDescription('Take a screenshot');
        $this->addArgument('url', InputArgument::OPTIONAL, 'A url to take a screenshot');
        $this->addOption('cache', 'c', InputOption::VALUE_OPTIONAL, 'Use cache (in seconds)');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var ScreenshotService $screenshotService */
        $screenshotService = $this->getContainer()->offsetGet('screenshot');

        $url = $input->getArgument('url');
        if (null === $url) {
            $url = $this->getContainer()->get('config')->get('url');
        }

        $cache = $input->getOption('cache');

        $screenshotService->take($url, $cache);
    }
}