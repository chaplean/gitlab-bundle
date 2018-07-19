<?php

namespace Chaplean\Bundle\GitlabBundle\Form\Type;

use Chaplean\Bundle\FrontBundle\Form\Type\FloatType;
use Chaplean\Bundle\GitlabBundle\Model\PipelineModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PipelineType.
 *
 * @package   Chaplean\Bundle\GitlabBundle\Form\Type
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class PipelineType extends AbstractType
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
        $builder
            ->add(
                'id',
                IntegerType::class
            )
            ->add(
                'ref',
                TextType::class,
                ['property_path' => 'branch']
            )
            ->add(
                'status',
                TextType::class
            )
            ->add(
                'sha',
                TextType::class
            )
            ->add(
                'coverage',
                FloatType::class,
                [
                    'scale' => 1,
                ]
            )
        ;
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
                'data_class'         => PipelineModel::class,
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
        return 'webhook_pipeline_form';
    }
}
