<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\RouteBundle\Application\Command\Route;

use Zentlix\MainBundle\Infrastructure\Share\Bus\UpdateCommandInterface;
use Zentlix\RouteBundle\Domain\Route\Entity\Route;

class UpdateCommand extends Command implements UpdateCommandInterface
{
    public function __construct(Route $route)
    {
        $this->entity     = $route;
        $this->url        = $route->getUrl();
        $this->title      = $route->getTitle();
        $this->name       = $route->getName();
        $this->template   = $route->getTemplate();
        $this->active     = $route->isActive();
        $this->site       = $route->getSite()->getId()->toString();
        $this->bundle     = $route->getBundle()->getId()->toString();
        $this->controller = $route->getController();
        $this->action     = $route->getAction();
    }
}