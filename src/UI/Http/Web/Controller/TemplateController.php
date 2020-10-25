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
use Zentlix\RouteBundle\Application\Query\Template\TemplateQuery;
use function is_null;

class TemplateController extends AbstractController
{
    public function resolve($_route, string $code = null): Response
    {
        $template = $this->ask(new TemplateQuery($_route));

        if(is_null($template)) {
            throw $this->createNotFoundException();
        }

        return $this->render($template, ['parameter' => $code]);
    }
}