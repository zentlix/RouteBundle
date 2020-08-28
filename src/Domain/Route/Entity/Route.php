<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\RouteBundle\Domain\Route\Entity;

use Doctrine\ORM\Mapping;
use Zentlix\MainBundle\Domain\Shared\Entity\Eventable;
use Zentlix\MainBundle\Domain\Bundle\Entity\Bundle;
use Zentlix\MainBundle\Domain\Site\Entity\Site;
use Zentlix\RouteBundle\Application\Command\Route\CreateCommand;
use Zentlix\RouteBundle\Application\Command\Route\UpdateCommand;

/**
 * @Mapping\Entity(repositoryClass="Zentlix\RouteBundle\Domain\Route\Repository\RouteRepository")
 * @Mapping\Table(name="zentlix_route_routes")
 */
class Route implements Eventable
{
    /**
     * @Mapping\Id()
     * @Mapping\GeneratedValue()
     * @Mapping\Column(type="integer")
     */
    private $id;

    /** @Mapping\Column(type="string", length=255) */
    private $url;

    /** @Mapping\Column(type="string", length=255) */
    private $controller;

    /** @Mapping\Column(type="string", length=255) */
    private $action;

    /** @Mapping\Column(type="string", length=255) */
    private $title;

    /** @Mapping\Column(type="string", length=255, unique=true) */
    private $name;

    /** @Mapping\Column(type="string", length=255, nullable=true) */
    private $template;

    /**
     * @var Site
     * @Mapping\ManyToOne(targetEntity="Zentlix\MainBundle\Domain\Site\Entity\Site", inversedBy="routes")
     * @Mapping\JoinColumn(name="site_id", referencedColumnName="id", nullable=false)
     */
    private $site;

    /**
     * @var Bundle
     * @Mapping\ManyToOne(targetEntity="Zentlix\MainBundle\Domain\Bundle\Entity\Bundle")
     * @Mapping\JoinColumn(name="bundle_id", referencedColumnName="id", nullable=false)
     */
    private $bundle;

    public function __construct(CreateCommand $command)
    {
        $this->setValuesFromCommands($command);

        $this->name = $command->name;
        $this->bundle = $command->bundle;
    }

    public function update(UpdateCommand $command)
    {
        $this->setValuesFromCommands($command);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getCleanUrl(): string
    {
        return self::cleanUrl($this->url);
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSite(): Site
    {
        return $this->site;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function isUrlEqual(string $url): bool
    {
        return self::cleanUrl($url) === self::cleanUrl($this->url);
    }

    public static function cleanUrl(string $url): string
    {
        return explode('{', $url)[0];
    }

    /**
     * @param CreateCommand|UpdateCommand $command
     */
    private function setValuesFromCommands($command): void
    {
        if($command->url[0] === '/') {
            $command->url = substr($command->url, 1);
        }

        $this->url = $command->url;
        $this->controller = $command->controller;
        $this->action = $command->action;
        $this->title = $command->title;
        $this->site = $command->site;
        $this->template = $command->template;
    }
}