<?php

namespace Chaplean\Bundle\GitlabBundle\Form\Type;

use Chaplean\Bundle\GitlabBundle\Model\BuildModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BuildType.
 *
 * @package   Chaplean\Bundle\GitlabBundle\Form\Type
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class BuildType extends AbstractType
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
                'stage',
                TextType::class
            )
            ->add(
                'status',
                TextType::class
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
                'data_class'         => BuildModel::class,
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
        return 'webhook_build_form';
    }
}
