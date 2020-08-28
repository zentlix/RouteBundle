<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\RouteBundle\Application\Command\Path;

use Zentlix\MainBundle\Application\Command\UpdateCommandInterface;
use Zentlix\MainBundle\Infrastructure\Share\Bus\CommandInterface;

class UpdateCommand implements UpdateCommandInterface, CommandInterface
{
    private array $entity;
    public array $routes;
    public int $site;

    public function __construct($routes, int $site)
    {
        $this->routes = $routes;
        $this->entity = $routes;
        $this->site = $site;
    }

    public function getEntity(): array
    {
        return $this->entity;
    }
}