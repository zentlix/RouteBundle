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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Zentlix\RouteBundle\Application\Command\Route\UpdateCommand;
use Zentlix\RouteBundle\Domain\Route\Event\Route\UpdateForm as UpdateFormEvent;

class UpdateForm extends Form
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder->remove('name');

        $this->eventDispatcher->dispatch(new UpdateFormEvent($builder));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'     =>  UpdateCommand::class,
            'label'          => 'zentlix_route.route.update.process',
            'form'           =>  self::SIMPLE_FORM,
            'deleteBtnLabel' => 'zentlix_route.route.delete.action',
            'deleteConfirm'  => 'zentlix_route.route.delete.confirmation'
        ]);
    }
}