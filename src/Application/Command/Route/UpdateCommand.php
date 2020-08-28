<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\RouteBundle\Application\Command\Route;

use Symfony\Component\HttpFoundation\Request;
use Zentlix\MainBundle\Application\Command\UpdateCommandInterface;
use Zentlix\RouteBundle\Domain\Route\Entity\Route;

class UpdateCommand extends Command implements UpdateCommandInterface
{
    public function __construct(Route $route, Request $request)
    {
        $this->entity = $route;
        $this->url = $request->request->get('url', $route->getUrl());
        $this->controller = $request->request->get('controller', $route->getController());
        $this->action = $request->request->get('action', $route->getAction());
        $this->title = $request->request->get('title', $route->getTitle());
        $this->name = $request->request->get('name', $route->getName());
        $this->template = $request->request->get('template', $route->getTemplate());
        $this->site = (int) $request->request->get('site', $route->getSite()->getId());
    }
}