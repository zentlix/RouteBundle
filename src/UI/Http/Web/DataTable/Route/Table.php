<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\RouteBundle\UI\Http\Web\DataTable\Route;

use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Zentlix\MainBundle\Domain\DataTable\Column\TextColumn;
use Zentlix\MainBundle\Infrastructure\Share\DataTable\AbstractDataTableType;
use Zentlix\RouteBundle\Domain\Route\Event\Route\Table as TableEvent;
use Zentlix\RouteBundle\Domain\Route\Entity\Route;

class Table extends AbstractDataTableType
{
    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable->setName($this->router->generate('admin.route.list'));
        $dataTable->setTitle('zentlix_route.route.routes');
        $dataTable->setCreateBtnLabel('zentlix_route.route.create.action');

        $dataTable
            ->add('id', TextColumn::class, ['label' => 'zentlix_main.id', 'visible' => true])
            ->add('title', TextColumn::class,
                [
                    'render'    => fn($value, Route $context) =>
                        sprintf('<a href="%s">%s</a>', $this->router->generate('admin.route.update', ['id' => $context->getId()]), $this->translator->trans($value)),
                    'visible'   => true,
                    'label'     => 'zentlix_main.title'
                ])

            ->add('url', TextColumn::class, [
                'data'    => fn(Route $route) => sprintf('https://%s/%s', $route->getSite()->getUrl(), $route->getUrl()),
                'label'   => 'zentlix_main.site.url',
                'visible' => true
            ])
            ->add('controller', TextColumn::class, [
                'label'   => 'zentlix_route.controller',
                'visible' => false
            ])
            ->add('action', TextColumn::class, [
                'label'   => 'zentlix_route.action',
                'visible' => false
            ])
            ->add('name', TextColumn::class, [
                'label'   => 'zentlix_route.route.name',
                'visible' => false
            ])
            ->addOrderBy($dataTable->getColumnByName('id'), $dataTable::SORT_DESCENDING)
            ->createAdapter(ORMAdapter::class, ['entity' => Route::class]);

        $this->eventDispatcher->dispatch(new TableEvent($dataTable));
    }
}