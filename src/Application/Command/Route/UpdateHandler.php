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
use Zentlix\MainBundle\Domain\Site\Specification\ExistTemplateFileSpecification;
use Zentlix\MainBundle\Domain\Site\Repository\SiteRepository;
use Zentlix\MainBundle\Domain\Site\Specification\ExistSiteSpecification;
use Zentlix\RouteBundle\Domain\Cache\Service\Cache;
use Zentlix\RouteBundle\Domain\Route\Event\Route\BeforeUpdate;
use Zentlix\RouteBundle\Domain\Route\Event\Route\AfterUpdate;
use Zentlix\RouteBundle\Domain\Route\Specification\UniqueUrlSpecification;

class UpdateHandler implements CommandHandlerInterface
{
    private EntityManagerInterface $entityManager;
    private EventDispatcherInterface $eventDispatcher;
    private UniqueUrlSpecification $uniqueUrlSpecification;
    private ExistSiteSpecification $existSiteSpecification;
    private ExistTemplateFileSpecification $existTemplateFileSpecification;
    private SiteRepository $siteRepository;
    private Cache $cache;

    public function __construct(EntityManagerInterface $entityManager,
                                EventDispatcherInterface $eventDispatcher,
                                UniqueUrlSpecification $uniqueUrlSpecification,
                                ExistSiteSpecification $existSiteSpecification,
                                ExistTemplateFileSpecification $existTemplateFileSpecification,
                                SiteRepository $siteRepository,
                                Cache $cache)
    {
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->uniqueUrlSpecification = $uniqueUrlSpecification;
        $this->existSiteSpecification = $existSiteSpecification;
        $this->existTemplateFileSpecification = $existTemplateFileSpecification;
        $this->siteRepository = $siteRepository;
        $this->cache = $cache;
    }

    public function __invoke(UpdateCommand $command): void
    {
        $route = $command->getEntity();

        if($route->isUrlEqual($command->url) === false) {
            $this->uniqueUrlSpecification->isUnique($command->url, $command->site);
        }

        $this->existSiteSpecification->isExist($command->site);
        $command->site = $this->siteRepository->get($command->site);

        if($command->template) {
            $this->existTemplateFileSpecification->isExist($command->site->getTemplate()->getFolder() . '/' . $command->template);
        }

        $this->eventDispatcher->dispatch(new BeforeUpdate($command));

        $route->update($command);

        $this->entityManager->flush();

        $this->cache->clearRoutes();

        $this->eventDispatcher->dispatch(new AfterUpdate($route, $command));
    }
}