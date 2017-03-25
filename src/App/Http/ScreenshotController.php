<?php

namespace App\Http;

use App\Repository\ScreenshotRepository;
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
     * @var ScreenshotRepository
     */
    private $repository;

    public function __construct(Twig $view, ScreenshotRepository $repository)
    {
        $this->view = $view;
        $this->repository = $repository;
    }

    /**
     * @param $req
     * @param $resp
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function currentWeekAction($req, $resp)
    {
        $images = $this->repository->getCurrentWeek();

        return $this->view->render($resp, 'screenshot/images.twig', compact('images'));
    }

    public function calendarAction($req, $resp)
    {
        $calendar = $this->repository->getDaily();

        return $this->view->render($resp, 'screenshot/calendar_daily.twig', compact('calendar'));
    }

    public function dateAction($req, $resp, $args)
    {
        $images = $this->repository->getForDate($args['date']);

        return $this->view->render($resp, 'screenshot/images.twig', compact('images'));
    }
}