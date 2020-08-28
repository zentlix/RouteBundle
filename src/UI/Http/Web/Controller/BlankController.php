<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\RouteBundle\UI\Http\Web\Controller;

use Symfony\Component\HttpFoundation\Response;
use Zentlix\MainBundle\UI\Http\Web\Controller\AbstractController;
use Zentlix\RouteBundle\Application\Query\Blank\ElementQuery;
use Zentlix\RouteBundle\Application\Query\Blank\IndexQuery;

class BlankController extends AbstractController
{
    public function index($_route): Response
    {
        $template = $this->ask(new IndexQuery($_route));

        if(is_null($template)) {
            throw $this->createNotFoundException();
        }

        return $this->render($template);
    }

    public function element($_route, string $code): Response
    {
        $template = $this->ask(new ElementQuery($_route));

        if(is_null($template)) {
            throw $this->createNotFoundException();
        }

        return $this->render($template, ['code' => $code]);
    }
}