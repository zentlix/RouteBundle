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

use Zentlix\MainBundle\Infrastructure\Share\Bus\QueryInterface;

class TemplateQuery implements QueryInterface
{
    private string $route;

    public function __construct(string $route)
    {
        $this->route = $route;
    }

    public function getRoute(): string
    {
        return $this->route;
    }
}