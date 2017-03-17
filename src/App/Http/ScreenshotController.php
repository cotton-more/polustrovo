<?php

namespace App\Http;

use App\Service\ScreenshotService;
use Slim\Container;
use Slim\Views\Twig;

class ScreenshotController
{
    /**
     * @var Twig
     */
    private $view;

    /**
     * @var ScreenshotService
     */
    private $screenshotService;

    public function __construct(Twig $view, ScreenshotService $screenshotService)
    {
        $this->view = $view;
        $this->screenshotService = $screenshotService;
    }

    /**
     * @param $req
     * @param $resp
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function currentWeekAction($req, $resp)
    {
        $images = $this->screenshotService->getCurrentWeek();

        return $this->view->render($resp, 'screenshot/images.twig', compact('images'));
    }
}