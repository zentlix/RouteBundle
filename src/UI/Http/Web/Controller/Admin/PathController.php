<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\RouteBundle\UI\Http\Web\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Zentlix\MainBundle\Domain\Site\Repository\SiteRepository;
use Zentlix\MainBundle\UI\Http\Web\Controller\Admin\ResourceController;
use Zentlix\RouteBundle\Application\Command\Path\UpdateCommand;
use Zentlix\RouteBundle\Domain\Route\Repository\RouteRepository;
use Zentlix\RouteBundle\UI\Http\Web\Form\Path\UpdateForm;

class PathController extends ResourceController
{
    public static $updateSuccessMessage = 'zentlix_route.path.update.success';

    public function update(Request $request, SiteRepository $siteRepository, RouteRepository $routeRepository): Response
    {
        $request->request->get('site') ? $site = $siteRepository->find((int) $request->request->get('site')) :
            $site = $siteRepository->findOneBy([], ['sort' => 'ASC']);

        return $this->updateResource(new UpdateCommand($routeRepository->findBySiteId($site->getId()), $site->getId()), UpdateForm::class, $request);
    }
}
