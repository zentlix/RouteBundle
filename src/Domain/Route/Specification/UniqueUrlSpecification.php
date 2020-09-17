<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\RouteBundle\Domain\Route\Specification;

use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\Translation\TranslatorInterface;
use Zentlix\RouteBundle\Domain\Route\Entity\Route;
use Zentlix\RouteBundle\Domain\Route\Repository\RouteRepository;

final class UniqueUrlSpecification
{
    private TranslatorInterface $translator;
    private RouteRepository $routeRepository;

    public function __construct(TranslatorInterface $translator, RouteRepository $routeRepository)
    {
        $this->translator = $translator;
        $this->routeRepository = $routeRepository;
    }

    public function isUnique(string $url, int $siteId): void
    {
        $routes = $this->routeRepository->wherePathLike(Route::cleanUrl($url));

        /** @var Route $route */
        foreach ($routes as $route) {
            if($route->getSite()->getId() === $siteId) {
                if($route->getCleanUrl() === Route::cleanUrl($url)) {
                    throw new NonUniqueResultException(sprintf($this->translator->trans('zentlix_route.route.already_exist'), $url));
                }
            }
        }
    }

    public function __invoke(string $url, int $siteId): void
    {
        $this->isUnique($url, $siteId);
    }
}