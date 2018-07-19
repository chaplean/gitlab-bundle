<?php

namespace Chaplean\Bundle\GitlabBundle\Form\Type;

use Chaplean\Bundle\GitlabBundle\Model\WebhookPipelineEventModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class WebhookPipelineEventType.
 *
 * @package   Chaplean\Bundle\GitlabBundle\Form\Type
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class WebhookPipelineEventType extends AbstractType
{
    /**
     * Constructor model form
     *
     * @param FormBuilderInterface $builder Builder.
     * @param array                $options Options.
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'object_kind',
            TextType::class,
            ['property_path' => 'objectKind']
        );

        $builder->add(
            'object_attributes',
            PipelineType::class,
            ['property_path' => 'objectAttributes']
        );

        $builder->add(
            'builds',
            CollectionType::class,
            [
                'allow_add'    => true,
                'allow_delete' => true,
                'delete_empty' => false,
                'required'     => true,
                'entry_type'   => BuildType::class
            ]
        );

        $builder->add(
            'project',
            ProjectType::class
        );
    }

    /**
     * Set defaults option
     *
     * @param OptionsResolver $resolver Resolver.
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'         => WebhookPipelineEventModel::class,
                'csrf_protection'    => false,
                'allow_extra_fields' => true,
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'webhook_pipeline_event_form';
    }
}
