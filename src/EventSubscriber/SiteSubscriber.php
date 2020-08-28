<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\RouteBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zentlix\MainBundle\Application\Command\Site\DeleteCommand;
use Zentlix\MainBundle\Domain\Site\Entity\Site;
use Zentlix\MainBundle\Domain\Site\Event\Site\AfterCreate;
use Zentlix\MainBundle\Domain\Site\Event\Site\BeforeDelete;
use Zentlix\RouteBundle\Domain\Route\Service\Routes;

class SiteSubscriber implements EventSubscriberInterface
{
    private Routes $routes;

    public function __construct(Routes $routes)
    {
        $this->routes = $routes;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AfterCreate::class => 'onAfterCreate',
            BeforeDelete::class => 'onBeforeDelete'
        ];
    }

    public function onAfterCreate(AfterCreate $afterCreate): void
    {
        /** @var Site $site */
        $site = $afterCreate->getEntity();

        $this->routes->installRoutes($site);
    }

    public function onBeforeDelete(BeforeDelete $beforeDelete)
    {
        /** @var DeleteCommand $command */
        $command = $beforeDelete->getCommand();

        $this->routes->removeSiteRoutes($command->site->getId());
    }
}