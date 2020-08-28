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

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Zentlix\MainBundle\Application\Command\CommandHandlerInterface;
use Zentlix\RouteBundle\Domain\Cache\Service\Cache;
use Zentlix\RouteBundle\Domain\Route\Event\Route\AfterDelete;
use Zentlix\RouteBundle\Domain\Route\Event\Route\BeforeDelete;

class DeleteHandler implements CommandHandlerInterface
{
    private EntityManagerInterface $entityManager;
    private EventDispatcherInterface $eventDispatcher;
    private Cache $cache;

    public function __construct(EntityManagerInterface $entityManager,
                                EventDispatcherInterface $eventDispatcher,
                                Cache $cache)
    {
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->cache = $cache;
    }

    public function __invoke(DeleteCommand $command): void
    {
        $routeId = $command->route->getId();

        $this->eventDispatcher->dispatch(new BeforeDelete($command));

        $this->entityManager->remove($command->route);
        $this->entityManager->flush();

        $this->cache->clearRoutes();

        $this->eventDispatcher->dispatch(new AfterDelete($routeId));
    }
}