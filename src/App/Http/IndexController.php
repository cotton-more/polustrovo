<?php

namespace App\Http;

use App\Service\ScreenshotService;
use Slim\Container;
use Slim\Views\Twig;

class IndexController
{
    /**
     * @var Twig
     */
    private $view;

    /**
     * @var ScreenshotService
     */
    private $screenshotService;

    public function __construct(Container $container)
    {
        $this->view = $container->get('view');
        $this->screenshotService = $container->get('screenshot');
    }

    public function index($req, $resp)
    {
        $image = $this->screenshotService->getLatest();

        return $this->view->render($resp, 'index.twig', [
            'image' => $image,
        ]);
    }
}