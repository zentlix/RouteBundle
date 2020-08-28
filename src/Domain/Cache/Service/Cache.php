<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\RouteBundle\Domain\Cache\Service;

use Symfony\Component\Filesystem\Filesystem;
use Zentlix\MainBundle\Domain\Cache\Service\Cache as BaseCache;

class Cache extends BaseCache
{
    public function clearRoutes(): void
    {
        $fs = new Filesystem();
        if($fs->exists($this->cacheDir . DIRECTORY_SEPARATOR . 'UrlGenerator.php')) {
            $fs->remove($this->cacheDir . DIRECTORY_SEPARATOR . 'UrlGenerator.php');
        }
        if($fs->exists($this->cacheDir . DIRECTORY_SEPARATOR . 'UrlGenerator.php.meta')) {
            $fs->remove($this->cacheDir . DIRECTORY_SEPARATOR . 'UrlGenerator.php.meta');
        }
        if($fs->exists($this->cacheDir . DIRECTORY_SEPARATOR . 'UrlMatcher.php')) {
            $fs->remove($this->cacheDir . DIRECTORY_SEPARATOR . 'UrlMatcher.php');
        }
        if($fs->exists($this->cacheDir . DIRECTORY_SEPARATOR . 'UrlMatcher.php.meta')) {
            $fs->remove($this->cacheDir . DIRECTORY_SEPARATOR . 'UrlMatcher.php.meta');
        }
    }
}