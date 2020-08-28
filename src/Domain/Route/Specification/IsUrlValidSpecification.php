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
use Zentlix\MainBundle\Domain\Shared\Specification\AbstractSpecification;
use Zentlix\RouteBundle\Domain\Route\Entity\Route;

final class IsUrlValidSpecification extends AbstractSpecification
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function isValid(string $url): bool
    {
        return $this->isSatisfiedBy($url);
    }

    public function isSatisfiedBy($value): bool
    {
        if(!preg_match("/^[a-z0-9-\/]+$/", Route::cleanUrl($value))) {
            throw new \Exception(sprintf($this->translator->trans('zentlix_route.route.not_valid'), $value));
        }

        return true;
    }

    public function __invoke(string $url)
    {
        return $this->isValid($url);
    }
}