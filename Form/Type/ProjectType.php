<?php

namespace Chaplean\Bundle\GitlabBundle\Form\Type;

use Chaplean\Bundle\GitlabBundle\Model\ProjectModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ProjectType.
 *
 * @package   Chaplean\Bundle\GitlabBundle\Form\Type
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class ProjectType extends AbstractType
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
                IntegerType::class,
                ['property_path' => 'gitlabId']
            )
            ->add(
                'name',
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
                'data_class'         => ProjectModel::class,
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
        return 'project_form';
    }
}
