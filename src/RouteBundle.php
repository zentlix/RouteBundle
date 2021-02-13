<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\RouteBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Zentlix\MainBundle\ZentlixBundleInterface;
use Zentlix\MainBundle\ZentlixBundleTrait;
use Zentlix\RouteBundle\Application\Command;

class RouteBundle extends Bundle implements ZentlixBundleInterface
{
    use ZentlixBundleTrait;

    public function getTitle(): string
    {
        return 'zentlix_route.route.routes';
    }

    public function getVersion(): string
    {
        return '1.1.3';
    }

    public function getDeveloper(): array
    {
        return ['name' => 'Zentlix', 'url' => 'https://zentlix.io'];
    }

    public function getDescription(): string
    {
        return 'zentlix_route.bundle_description';
    }

    public function configureRights(): array
    {
        return [
            Command\Route\CreateCommand::class => 'zentlix_route.route.create.process',
            Command\Route\UpdateCommand::class => 'zentlix_route.route.update.process',
            Command\Route\DeleteCommand::class => 'zentlix_route.route.delete.process'
        ];
    }
}
