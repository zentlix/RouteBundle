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
use Zentlix\MainBundle\UI\Http\Web\FormType\AbstractForm;
use Zentlix\MainBundle\UI\Http\Web\Type;
use Zentlix\RouteBundle\Application\Command\Route\CreateCommand;
use Zentlix\RouteBundle\RouteBundle;

class Form extends AbstractForm
{
    protected EventDispatcherInterface $eventDispatcher;
    protected TranslatorInterface $translator;
    protected BundleRepository $bundleRepository;
    protected SiteRepository $siteRepository;

    public function __construct(EventDispatcherInterface $eventDispatcher,
                                TranslatorInterface $translator,
                                BundleRepository $bundleRepository,
                                SiteRepository $siteRepository)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->translator = $translator;
        $this->bundleRepository = $bundleRepository;
        $this->siteRepository = $siteRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var CreateCommand $command */
        $command = $builder->getData();
        $routeBundleId = $this->bundleRepository->getOneByClass(RouteBundle::class)->getId();
        if(is_null($command->site)) {
            $command->site = array_values($this->siteRepository->assoc())[0];
        }

        $builder
            ->add('url', Type\TextType::class, [
                'label'   =>'zentlix_main.site.site_url',
                'prepend' => (int) $command->site > 0 ? 'https://' . $this->siteRepository->get($command->site)->getUrl() . '/' : ''
            ])
            ->add('active', Type\CheckboxType::class, [
                'label' => 'zentlix_route.active'
            ])
            ->add('site', Type\HiddenType::class);

        // only for additional routes
        if($command->bundle === $routeBundleId || is_null($command->bundle)) {
            $builder
                ->add('title', Type\TextType::class, [
                    'label' => 'zentlix_main.title',
                    'data'  => $this->translator->trans($command->title)
                ])
                ->add('name', Type\TextType::class, [
                    'label' => 'zentlix_route.route.name',
                    'data'  => $this->translator->trans($command->name)
                ])
                ->add('bundle', Type\HiddenType::class, [
                    'data' => $routeBundleId
                ])
                ->add('template', Type\ChoiceType::class, [
                    'choices'  => $this->siteRepository->get($command->site)->getTemplate()->getConfigParam('route'),
                    'label'    => 'zentlix_main.template'
                ]);
        }
    }
}
