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

use Zentlix\MainBundle\UI\Http\Web\Controller\AbstractController;

class Controllers
{
    private array $controllers = [];

    public function __construct(iterable $controllers)
    {
        foreach ($controllers as $controller) {
            $this->addController($controller);
        }
    }

    public function addController(AbstractController $controller)
    {
        $this->controllers[\get_class($controller)] = $controller;
    }

    public function getActions(string $hash): array
    {
        if(!isset($this->controllers[$hash])) {
            throw new \Exception(sprintf('Controller %s not found.', $hash));
        }

        $methods = [];
        foreach ((new \ReflectionClass($this->controllers[$hash]))->getMethods() as $method) {
            if ($method->class === \get_class($this->controllers[$hash])) {
                $methods[$method->name] = $method->name;
            }
        }

        return $methods;
    }

    public function assoc(): array
    {
        $providers = array_map(fn(AbstractController $controller) => [
            'class'  => \get_class($controller),
            'title'  => \get_class($controller)
        ], $this->controllers);

        return array_column($providers, 'class', 'title');
    }
}