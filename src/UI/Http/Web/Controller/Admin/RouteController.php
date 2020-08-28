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
use Zentlix\MainBundle\UI\Http\Web\Controller\Admin\ResourceController;
use Zentlix\RouteBundle\Application\Query\Route\DataTableQuery;
use Zentlix\RouteBundle\Application\Command\Route\CreateCommand;
use Zentlix\RouteBundle\Application\Command\Route\UpdateCommand;
use Zentlix\RouteBundle\Domain\Route\Entity\Route;
use Zentlix\RouteBundle\UI\Http\Web\DataTable\Route\Table;
use Zentlix\RouteBundle\UI\Http\Web\Form\Route\CreateForm;
use Zentlix\RouteBundle\Application\Command\Route\DeleteCommand;
use Zentlix\RouteBundle\UI\Http\Web\Form\Route\UpdateForm;

class RouteController extends ResourceController
{
    public static $createSuccessMessage = 'zentlix_route.route.create.success';
    public static $updateSuccessMessage = 'zentlix_route.route.update.success';
    public static $deleteSuccessMessage = 'zentlix_route.route.delete.success';
    public static $redirectAfterAction  = 'admin.paths.update';

    public function index(Request $request): Response
    {
        return $this->listResource(new DataTableQuery(Table::class), $request);
    }

    public function create(Request $request): Response
    {
        return $this->createResource(new CreateCommand($request), CreateForm::class, $request);
    }

    public function update(Route $route, Request $request): Response
    {
        return $this->updateResource(new UpdateCommand($route, $request), UpdateForm::class, $request);
    }

    public function delete(Route $route): Response
    {
        return $this->deleteResource(new DeleteCommand($route));
    }
}