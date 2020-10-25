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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Zentlix\MainBundle\Domain\Site\Repository\SiteRepository;
use Zentlix\MainBundle\UI\Http\Web\Controller\Admin\ResourceController;
use Zentlix\RouteBundle\Application\Command\Route\CreateCommand;
use Zentlix\RouteBundle\Application\Command\Route\UpdateCommand;
use Zentlix\RouteBundle\Application\Command\Route\DeleteCommand;
use Zentlix\RouteBundle\Domain\Route\Entity\Route;
use Zentlix\RouteBundle\Domain\Route\Repository\RouteRepository;
use Zentlix\RouteBundle\UI\Http\Web\Form\Route\CreateForm;
use Zentlix\RouteBundle\UI\Http\Web\Form\Route\UpdateForm;

class RouteController extends ResourceController
{
    public static $redirectAfterAction = 'admin.route.list';
    public static $redirectErrorPath   = 'admin.route.list';

    public function index(RouteRepository $routeRepository, SiteRepository $siteRepository, Request $request): Response
    {
        $sites = $siteRepository->assoc();
        $siteId = (int) $request->get('site', array_values($sites)[0]);
        $routes = $routeRepository->findBySiteId($siteId);

        $forms = [];
        foreach ($routes as $route) {
            $forms[$route->getId()] = $this->createForm(UpdateForm::class, new UpdateCommand($route))->createView();
        }

        $createCommand = new CreateCommand();
        $createCommand->site = $siteId;

        return $this->render('@RouteBundle/admin/routes/index.html.twig', [
            'site_id'     => $siteId,
            'sites'       => $sites,
            'routes'      => $routes,
            'forms'       => $forms,
            'create_form' => $this->createForm(CreateForm::class, $createCommand)->createView()
        ]);
    }

    public function create(Request $request): Response
    {
        try {
            $command = new CreateCommand();
            $form = $this->createForm(CreateForm::class, $command)->handleRequest($request);

            if($form->getErrors(true)->count() > 0) {
                $this->addFlash('error', $form->getErrors(true)->current()->getMessage());
            }

            if ($form->isSubmitted() && $form->isValid()) {
                $this->exec($command);
                $this->addFlash('success', $this->translator->trans('zentlix_route.route.create.success'));
            }
        } catch (\Exception $e) {
            return $this->redirectError($e->getMessage());
        }

        return $this->redirectToRoute('admin.route.list', ['site' => $request->get('site')]);
    }

    public function update(Route $route, Request $request): Response
    {
        try {
            $command = new UpdateCommand($route);
            $form = $this->createForm(UpdateForm::class, $command)->handleRequest($request);

            if($form->getErrors(true)->count() > 0) {
                $this->addFlash('error', $form->getErrors(true)->current()->getMessage());
            }

            if ($form->isSubmitted() && $form->isValid()) {
                $this->exec($command);
                $this->addFlash('success', $this->translator->trans('zentlix_route.route.update.success'));
            }
        } catch (\Exception $e) {
            return $this->redirectError($e->getMessage());
        }

        return $this->redirectToRoute('admin.route.list', ['site' => $request->get('site')]);
    }

    public function delete(Route $route): Response
    {
        self::$deleteSuccessMessage = 'zentlix_route.route.delete.success';

        return $this->deleteResource(new DeleteCommand($route));
    }
}