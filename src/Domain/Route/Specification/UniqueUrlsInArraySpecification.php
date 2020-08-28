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

final class UniqueUrlsInArraySpecification extends AbstractSpecification
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function isUnique(array $urls): bool
    {
        return $this->isSatisfiedBy($urls);
    }

    public function isSatisfiedBy($value): bool
    {
        /** @var Route $route */
        foreach ($value as $key => $route) {
            $value[$key] = $route->getCleanUrl();
        }

        $countArr = array_count_values($value);

        foreach ($countArr as $count) {
            if($count > 1) {
                throw new NonUniqueResultException($this->translator->trans('zentlix_route.route.not_unique'));
            }
        }

        return true;
    }

    public function __invoke(array $urls)
    {
        return $this->isUnique($urls);
    }
}