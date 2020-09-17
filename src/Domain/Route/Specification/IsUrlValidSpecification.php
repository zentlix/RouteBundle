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
use Zentlix\RouteBundle\Domain\Route\Entity\Route;

final class IsUrlValidSpecification
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function isValid(string $url): void
    {
        if(!preg_match("/^[a-z0-9-\/]+$/", Route::cleanUrl($url))) {
            throw new \DomainException(sprintf($this->translator->trans('zentlix_route.route.not_valid'), $url));
        }
    }

    public function __invoke(string $url): void
    {
        $this->isValid($url);
    }
}