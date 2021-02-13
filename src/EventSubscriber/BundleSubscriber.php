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
use Zentlix\MainBundle\Domain\Bundle\Event\AfterInstall;
use Zentlix\MainBundle\Domain\Bundle\Event\BeforeRemove;
use Zentlix\MainBundle\Domain\Bundle\Service\Bundles;
use Zentlix\MainBundle\Domain\Site\Repository\SiteRepository;
use Zentlix\RouteBundle\Domain\Route\Service\Routes;
use Zentlix\RouteBundle\Infrastructure\Share\RouteSupportInterface;

class BundleSubscriber implements EventSubscriberInterface
{
    private Bundles $bundles;
    private Routes $routes;
    private SiteRepository $siteRepository;

    public function __construct(Bundles $bundles,
                                Routes $routes,
                                SiteRepository $siteRepository)
    {
        $this->bundles = $bundles;
        $this->routes = $routes;
        $this->siteRepository = $siteRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AfterInstall::class => 'onAfterInstall',
            BeforeRemove::class => 'onBeforeRemove'
        ];
    }

    public function onAfterInstall(AfterInstall $afterInstall): void
    {
        $bundleEntity = $afterInstall->getBundle();
        $bundle = $this->bundles->getByClass($bundleEntity->getClass());

        if($bundle instanceof RouteSupportInterface) {
            foreach ($this->siteRepository->findAll() as $site) {
                $this->routes->installRoutesForSite($bundle->installFrontendRoutes(), $site, $bundleEntity);
            }
        }
    }

    public function onBeforeRemove(BeforeRemove $beforeRemove): void
    {
        $this->routes->removeBundleRoutes($beforeRemove->getCommand()->getBundle()->getId()->toString());
    }
}