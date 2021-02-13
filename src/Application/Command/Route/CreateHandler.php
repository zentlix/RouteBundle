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
use Zentlix\MainBundle\Domain\Bundle\Specification\ExistBundleSpecification;
use Zentlix\MainBundle\Domain\Site\Repository\SiteRepository;
use Zentlix\MainBundle\Domain\Site\Specification\ExistSiteSpecification;
use Zentlix\MainBundle\Domain\Template\Specification\ExistFileSpecification;
use Zentlix\MainBundle\Infrastructure\Share\Bus\CommandHandlerInterface;
use Zentlix\RouteBundle\Domain\Cache\Service\Cache;
use Zentlix\RouteBundle\Domain\Route\Entity\Route;
use Zentlix\RouteBundle\Domain\Route\Event\Route\AfterCreate;
use Zentlix\RouteBundle\Domain\Route\Event\Route\BeforeCreate;
use Zentlix\RouteBundle\Domain\Route\Specification\UniqueNameSpecification;
use Zentlix\RouteBundle\Domain\Route\Specification\UniqueUrlSpecification;

class CreateHandler implements CommandHandlerInterface
{
    private EntityManagerInterface $entityManager;
    private EventDispatcherInterface $eventDispatcher;
    private UniqueUrlSpecification $uniqueUrlSpecification;
    private UniqueNameSpecification $uniqueNameSpecification;
    private ExistBundleSpecification $existBundleSpecification;
    private ExistSiteSpecification $existSiteSpecification;
    private ExistFileSpecification $existFileSpecification;
    private SiteRepository $siteRepository;
    private Cache $cache;

    public function __construct(EntityManagerInterface $entityManager,
                                EventDispatcherInterface $eventDispatcher,
                                UniqueUrlSpecification $uniqueUrlSpecification,
                                UniqueNameSpecification $uniqueNameSpecification,
                                ExistBundleSpecification $existBundleSpecification,
                                ExistSiteSpecification $existSiteSpecification,
                                ExistFileSpecification $existFileSpecification,
                                SiteRepository $siteRepository,
                                Cache $cache)
    {
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->uniqueUrlSpecification = $uniqueUrlSpecification;
        $this->uniqueNameSpecification = $uniqueNameSpecification;
        $this->existBundleSpecification = $existBundleSpecification;
        $this->existSiteSpecification = $existSiteSpecification;
        $this->existFileSpecification = $existFileSpecification;
        $this->siteRepository = $siteRepository;
        $this->cache = $cache;
    }

    public function __invoke(CreateCommand $command): void
    {
        $this->uniqueUrlSpecification->isUnique($command->url, $command->site);
        $this->uniqueNameSpecification->isUnique($command->name . '_' . $command->site);
        $this->existBundleSpecification->isExist($command->bundle);
        $this->existSiteSpecification->isExist($command->site);

        if($command->template) {
            $this->existFileSpecification->isExist(
                $this->siteRepository->get($command->site)->getTemplate()->getFolder() . '/' . $command->template
            );
        }

        $this->eventDispatcher->dispatch(new BeforeCreate($command));

        $route = new Route($command);

        $this->entityManager->persist($route);
        $this->entityManager->flush();

        $this->cache->clearRoutes();

        $this->eventDispatcher->dispatch(new AfterCreate($route, $command));
    }
}