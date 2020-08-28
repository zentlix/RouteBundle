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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;
use Zentlix\MainBundle\Application\Command\CreateCommandInterface;

class CreateCommand extends Command implements CreateCommandInterface
{
    /** @Constraints\NotBlank() */
    public ?string $name;

    public function __construct(Request $request = null)
    {
        if($request) {
            $this->url = $request->request->get('url');
            $this->controller = $request->request->get('controller');
            $this->action = $request->request->get('action');
            $this->title = $request->request->get('title');
            $this->name = $request->request->get('name');
            $this->template = $request->request->get('template');
            $this->site = (int) $request->request->get('site');
        }
    }
}