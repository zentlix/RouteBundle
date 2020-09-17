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
use Zentlix\RouteBundle\Domain\Route\Repository\RouteRepository;
use function is_null;

final class ExistRouteSpecification
{
    private RouteRepository $routeRepository;
    private TranslatorInterface $translator;

    public function __construct(RouteRepository $routeRepository, TranslatorInterface $translator)
    {
        $this->routeRepository = $routeRepository;
        $this->translator = $translator;
    }

    public function isExist(int $routeId): void
    {
        if(is_null($this->routeRepository->find($routeId))) {
            throw new NotFoundException(sprintf($this->translator->trans('zentlix_route.route.not_exist'), $routeId));
        }
    }

    public function __invoke(int $routeId): void
    {
        $this->isExist($routeId);
    }
}