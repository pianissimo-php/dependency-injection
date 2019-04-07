<?php

namespace App\Pianissimo\Component\Routing;

use App\Pianissimo\Component\Allegro\Allegro;
use App\Pianissimo\Component\Allegro\Exception\TemplateNotFoundException;
use App\Pianissimo\Component\HttpFoundation\Exception\NotFoundHttpException;
use App\Pianissimo\Component\HttpFoundation\RedirectResponse;
use App\Pianissimo\Component\HttpFoundation\Response;

class ControllerService
{
    /** @var RoutingService */
    private $routingService;

    /** @var Allegro */
    private $allegro;

    public function __construct(RoutingService $routingService, Allegro $allegro)
    {
        $this->routingService = $routingService;
        $this->allegro = $allegro;
    }

    /**
     * @throws NotFoundHttpException
     */
    public function redirectToRoute(string $routeName): RedirectResponse
    {
        $route = $this->routingService->getRoute($routeName);
        return new RedirectResponse($route->getPath());
    }

    /**
     * @throws TemplateNotFoundException
     */
    public function render(string $template): Response
    {
        return new Response($this->allegro->render($template));
    }
}