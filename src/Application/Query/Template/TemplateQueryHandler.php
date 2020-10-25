<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\RouteBundle\Application\Query\Template;

use Zentlix\MainBundle\Infrastructure\Share\Bus\QueryHandlerInterface;
use Zentlix\RouteBundle\Domain\Route\Repository\RouteRepository;

class TemplateQueryHandler implements QueryHandlerInterface
{
    private RouteRepository $routeRepository;

    public function __construct(RouteRepository $routeRepository)
    {
        $this->routeRepository = $routeRepository;
    }

    public function __invoke(TemplateQuery $templateQuery)
    {
        $route = $this->routeRepository->findOneByName($templateQuery->getRoute());

        return $route ? $route->getTemplate() : null;
    }
}