<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\RouteBundle\UI\Http\Web\Form\Path;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Zentlix\MainBundle\Domain\Site\Repository\SiteRepository;
use Zentlix\MainBundle\UI\Http\Web\Type;
use Zentlix\MainBundle\UI\Http\Web\FormType\AbstractForm;
use Zentlix\RouteBundle\Application\Command\Path\UpdateCommand;
use Zentlix\RouteBundle\UI\Http\Web\FormType\RouteType;

class UpdateForm extends AbstractForm
{
    private SiteRepository $siteRepository;
    private UrlGeneratorInterface $router;

    public function __construct(SiteRepository $siteRepository, UrlGeneratorInterface $router)
    {
        $this->siteRepository = $siteRepository;
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('info', Type\NoticeType::class, ['data' => 'zentlix_route.route.info'])
            ->add('site', Type\ChoiceType::class, [
                'choices'  => $this->siteRepository->assoc(),
                'label' => 'zentlix_main.site.site',
                'required' => false,
                'update' => true
            ])
            ->add('routes', Type\CollectionType::class, [
                'entry_type' => RouteType::class,
                'entry_options' => ['label' => false],
                'by_reference' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'     => UpdateCommand::class,
            'label'          => 'zentlix_route.route.routes',
            'form'           => self::SIMPLE_FORM,
            'disable_delete' => true,
            'link'           => [
                'title' => 'zentlix_route.manage_routes',
                'url'   => $this->router->generate('admin.route.list')
            ]
        ]);
    }
}