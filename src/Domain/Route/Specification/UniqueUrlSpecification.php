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
use Zentlix\MainBundle\Domain\Shared\Specification\AbstractSpecification;
use Zentlix\RouteBundle\Domain\Route\Entity\Route;
use Zentlix\RouteBundle\Domain\Route\Repository\RouteRepository;

final class UniqueUrlSpecification extends AbstractSpecification
{
    private TranslatorInterface $translator;
    private RouteRepository $routeRepository;

    public function __construct(TranslatorInterface $translator, RouteRepository $routeRepository)
    {
        $this->translator = $translator;
        $this->routeRepository = $routeRepository;
    }

    public function isUnique(string $url, int $siteId): bool
    {
        return $this->isSatisfiedBy([$url, $siteId]);
    }

    public function isSatisfiedBy($value): bool
    {
        $routes = $this->routeRepository->wherePathLike(Route::cleanUrl($value[0]));

        /** @var Route $route */
        foreach ($routes as $route) {
            if($route->getSite()->getId() === $value[1]) {
                if($route->getCleanUrl() === Route::cleanUrl($value[0])) {
                    throw new NonUniqueResultException(sprintf($this->translator->trans('zentlix_route.route.already_exist'), $value[0]));
                }
            }
        }

        return true;
    }

    public function __invoke(string $url, int $siteId)
    {
        return $this->isUnique($url, $siteId);
    }
}