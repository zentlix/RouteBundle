<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\RouteBundle\Domain\Route\Service;

use Doctrine\ORM\EntityManagerInterface;
use Zentlix\MainBundle\Domain\Bundle\Entity\Bundle;
use Zentlix\MainBundle\Domain\Bundle\Repository\BundleRepository;
use Zentlix\MainBundle\Domain\Bundle\Service\Bundles;
use Zentlix\MainBundle\Domain\Site\Entity\Site;
use Zentlix\MainBundle\Infrastructure\Share\Bus\CommandBus;
use Zentlix\RouteBundle\Domain\Route\Entity\Route;
use Zentlix\RouteBundle\Infrastructure\Share\RouteSupportInterface;

class Routes
{
    private EntityManagerInterface $entityManager;
    private Bundles $bundles;
    private CommandBus $commandBus;

    public function __construct(EntityManagerInterface $entityManager, Bundles $bundles, CommandBus $commandBus)
    {
        $this->entityManager = $entityManager;
        $this->bundles = $bundles;
        $this->commandBus = $commandBus;
    }

    public function installRoutes(Site $site): void
    {
        /** @var BundleRepository $bundleRepository */
        $bundleRepository = $this->entityManager->getRepository(Bundle::class);
        $bundles = $bundleRepository->findAll();

        foreach ($bundles as $bundle) {
            $kernel = $this->bundles->getByClass($bundle->getClass());
            if($kernel instanceof RouteSupportInterface) {
                $this->installRoutesForSite($kernel->installFrontendRoutes(), $site, $bundle);
            }
        }
    }

    public function installRoutesForSite(array $commands, Site $site, Bundle $bundle): void
    {
        foreach ($commands as $command) {
            $command->site = $site->getId();
            $command->bundle = $bundle->getId();

            $this->commandBus->handle($command);
        }
    }

    public function removeSiteRoutes(int $siteId): void
    {
        $repository = $this->entityManager->getRepository(Route::class);

        foreach ($repository->findBySiteId($siteId) as $route) {
            $this->entityManager->remove($route);
        }

        $this->entityManager->flush();
    }

    public function removeBundleRoutes(int $bundleId): void
    {
        $repository = $this->entityManager->getRepository(Route::class);

        foreach ($repository->findByBundleId($bundleId) as $route) {
            $this->entityManager->remove($route);
        }

        $this->entityManager->flush();
    }
}