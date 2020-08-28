<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\RouteBundle\EventListener;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use Zentlix\MainBundle\Domain\Bundle\Repository\BundleRepository;
use Zentlix\MainBundle\Domain\Site\Repository\SiteRepository;
use Zentlix\RouteBundle\Domain\Route\Entity\Route;

class RouteListener
{
    private BundleRepository $bundleRepository;
    private SiteRepository $siteRepository;

    public function __construct(BundleRepository $bundleRepository, SiteRepository $siteRepository)
    {
        $this->bundleRepository = $bundleRepository;
        $this->siteRepository = $siteRepository;
    }

    public function prePersist(Route $route, LifecycleEventArgs $event): void
    {
        $refBundleProperty = new \ReflectionProperty(Route::class, 'bundle');
        $refSiteProperty = new \ReflectionProperty(Route::class, 'site');
        $refNameProperty = new \ReflectionProperty(Route::class, 'name');

        $refBundleProperty->setAccessible(true);
        $refSiteProperty->setAccessible(true);
        $refNameProperty->setAccessible(true);

        $refNameProperty->setValue($route, $route->getName() . '_' . $refSiteProperty->getValue($route));

        if(\is_int($refBundleProperty->getValue($route))) {
            $refBundleProperty->setValue($route, $this->bundleRepository->get($refBundleProperty->getValue($route)));
        }

        if(\is_int($refSiteProperty->getValue($route))) {
            $refSiteProperty->setValue($route, $this->siteRepository->get($refSiteProperty->getValue($route)));
        }
    }
}