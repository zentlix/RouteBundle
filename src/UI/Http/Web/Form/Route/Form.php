<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\RouteBundle\UI\Http\Web\Form\Route;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Zentlix\MainBundle\Domain\Bundle\Repository\BundleRepository;
use Zentlix\MainBundle\Domain\Site\Repository\SiteRepository;
use Zentlix\MainBundle\MainBundle;
use Zentlix\MainBundle\UI\Http\Web\FormType\AbstractForm;
use Zentlix\MainBundle\UI\Http\Web\Type;
use Zentlix\RouteBundle\Application\Command\Route\CreateCommand;
use Zentlix\RouteBundle\Domain\Route\Service\Controllers;
use Zentlix\RouteBundle\UI\Http\Web\Controller\BlankController;

class Form extends AbstractForm
{
    protected EventDispatcherInterface $eventDispatcher;
    protected TranslatorInterface $translator;
    protected Controllers $controllers;
    protected BundleRepository $bundleRepository;
    protected SiteRepository $siteRepository;

    public function __construct(EventDispatcherInterface $eventDispatcher,
                                TranslatorInterface $translator,
                                Controllers $controllers,
                                BundleRepository $bundleRepository,
                                SiteRepository $siteRepository)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->translator = $translator;
        $this->controllers = $controllers;
        $this->bundleRepository = $bundleRepository;
        $this->siteRepository = $siteRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var CreateCommand $command */
        $command = $builder->getData();

        $builder
            ->add('site', Type\ChoiceType::class, [
                'choices'  => $this->siteRepository->assoc(),
                'label'    => 'zentlix_main.site.site',
                'update'   => true
            ])
            ->add('url', Type\TextType::class, [
                'label'   =>'zentlix_main.site.site_url',
                'prepend' => (int) $command->site > 0 ? 'https://' . $this->siteRepository->get($command->site)->getUrl() . '/' : ''
            ])
            ->add('title', Type\TextType::class, [
                'label' => 'zentlix_main.title'
            ])
            ->add('controller', Type\ChoiceType::class, [
                'label'   => 'zentlix_route.controller',
                'choices' => $this->controllers->assoc(),
                'update'  => true
            ])
            ->add('name', Type\TextType::class, [
                'label'    => 'zentlix_route.route.name'
            ])
            ->add('bundle', Type\HiddenType::class, [
                'data' => $this->bundleRepository->getOneByClass(MainBundle::class)->getId()
            ]);

        if($command->controller) {
            $builder->add('action', Type\ChoiceType::class, [
                'label'   => 'zentlix_main.action',
                'choices' => $this->controllers->getActions($command->controller)
            ]);
        }

        if((int) $command->site > 0 && $command->controller === BlankController::class) {
            $builder->add('template', Type\ChoiceType::class, [
                'choices'  => $this->siteRepository->get($command->site)->getTemplate()->getConfigParam('route'),
                'label'    => 'zentlix_main.template',
                'required' => false
            ]);
        }

    }
}
