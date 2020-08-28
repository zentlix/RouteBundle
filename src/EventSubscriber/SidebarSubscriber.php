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
use Zentlix\MainBundle\Domain\AdminSidebar\Event\AfterBuild;

class SidebarSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            AfterBuild::class => 'sidebar',
        ];
    }

    public function sidebar(AfterBuild $afterBuild): void
    {
        $sidebar = $afterBuild->getSidebar();

        $settings = $sidebar->getMenuItem('zentlix_main.settings');

        $settings
            ->addChildren('zentlix_route.route.routes')
            ->generateUrl('admin.paths.update')
            ->sort(125);
    }
}