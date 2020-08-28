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

use Symfony\Contracts\Translation\TranslatorInterface;
use Zentlix\MainBundle\Application\Query\NotFoundException;
use Zentlix\MainBundle\Domain\Shared\Specification\AbstractSpecification;
use Zentlix\RouteBundle\Domain\Route\Repository\RouteRepository;

final class ExistRouteSpecification extends AbstractSpecification
{
    private RouteRepository $routeRepository;
    private TranslatorInterface $translator;

    public function __construct(RouteRepository $routeRepository, TranslatorInterface $translator)
    {
        $this->routeRepository = $routeRepository;
        $this->translator = $translator;
    }

    public function isExist(int $routeId): bool
    {
        return $this->isSatisfiedBy($routeId);
    }

    public function isSatisfiedBy($value): bool
    {
        $route = $this->routeRepository->find($value);

        if(is_null($route)) {
            throw new NotFoundException(\sprintf($this->translator->trans('zentlix_route.route.not_exist'), $value));
        }

        return true;
    }

    public function __invoke(int $routeId)
    {
        return $this->isExist($routeId);
    }
}