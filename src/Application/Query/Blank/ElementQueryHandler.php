<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\RouteBundle\Application\Query\Blank;

use Zentlix\MainBundle\Application\Query\QueryHandlerInterface;
use Zentlix\RouteBundle\Domain\Route\Repository\RouteRepository;

class ElementQueryHandler implements QueryHandlerInterface
{
    private RouteRepository $routeRepository;

    public function __construct(RouteRepository $routeRepository)
    {
        $this->routeRepository = $routeRepository;
    }

    public function __invoke(IndexQuery $indexQuery)
    {
        $route = $this->routeRepository->findOneByName($indexQuery->getRoute());

        return $route ? $route->getTemplate() : null;
    }
}