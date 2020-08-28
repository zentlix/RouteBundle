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
use Zentlix\RouteBundle\Domain\Route\Repository\RouteRepository;

final class UniqueNameSpecification extends AbstractSpecification
{
    private TranslatorInterface $translator;
    private RouteRepository $routeRepository;

    public function __construct(TranslatorInterface $translator, RouteRepository $routeRepository)
    {
        $this->translator = $translator;
        $this->routeRepository = $routeRepository;
    }

    public function isUnique(string $name): bool
    {
        return $this->isSatisfiedBy($name);
    }

    public function isSatisfiedBy($value): bool
    {
        $route = $this->routeRepository->findOneByName($value);

        if($route) {
            throw new NonUniqueResultException(sprintf($this->translator->trans('zentlix_route.route.name_already_exist'), $value));
        }

        return true;
    }

    public function __invoke(string $name)
    {
        return $this->isUnique($name);
    }
}