<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\RouteBundle\Domain\Route\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Routing\RouteLoaderInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Zentlix\RouteBundle\Domain\Route\Entity\Route as FrontendRoute;

class ZentlixRoutesLoader implements RouteLoaderInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function loadRoutes(): RouteCollection
    {
        $collection = new RouteCollection();

        $routeRepository = $this->entityManager->getRepository(FrontendRoute::class);
        $routes = $routeRepository->findAll();

        /** @var FrontendRoute $route */
        foreach ($routes as $route) {
            $defaults = [
                '_controller' => $route->getController() . '::' . $route->getAction(),
            ];

            $collection->add(
                $route->getName(),
                new Route($route->getUrl(), $defaults, $requirements = [], $options = [], $route->getSite()->getUrl())
            );
        }

        return $collection;
    }
}