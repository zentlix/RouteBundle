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

use Symfony\Component\Validator\Constraints;
use Zentlix\MainBundle\Application\Command\DynamicPropertyCommand;
use Zentlix\MainBundle\Domain\Site\Entity\Site;
use Zentlix\MainBundle\Infrastructure\Share\Bus\CommandInterface;
use Zentlix\RouteBundle\Domain\Route\Entity\Route;

class Command extends DynamicPropertyCommand implements CommandInterface
{
    /** @Constraints\NotBlank() */
    public ?string $url;
    /** @Constraints\NotBlank() */
    public ?string $controller;
    /** @Constraints\NotBlank() */
    public ?string $action;
    /** @Constraints\NotBlank() */
    public ?string $title;
    public ?string $name;
    public ?string $template = null;
    /** @var int|Site */
    public $site;
    public ?int $bundle;
    protected ?Route $entity;

    public function getEntity(): Route
    {
        return $this->entity;
    }
}