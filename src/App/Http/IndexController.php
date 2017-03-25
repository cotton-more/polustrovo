<?php

namespace App\Http;

use App\Repository\ScreenshotRepository;
use Slim\Views\Twig;

class IndexController
{
    /**
     * @var Twig
     */
    private $view;

    /**
     * @var ScreenshotRepository
     */
    private $screenshotRepository;

    public function __construct(Twig $view, ScreenshotRepository $repository)
    {
        $this->view = $view;
        $this->screenshotRepository = $repository;
    }

    public function index($req, $resp)
    {
        $image = $this->screenshotRepository->getLatest();

        return $this->view->render($resp, 'index.twig', [
            'image' => $image,
        ]);
    }
}