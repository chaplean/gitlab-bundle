<?php

namespace Chaplean\Bundle\GitlabBundle\Form\Type;


use Symfony\Component\Form\Extension\Core\DataTransformer\NumberToLocalizedStringTransformer;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class FloatType.
 *
 * @package   App\Bundle\FrontBundle\Form\Type
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class FloatType extends IntegerType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer(
            new NumberToLocalizedStringTransformer(
                $options['scale'],
                $options['grouping'],
                $options['rounding_mode']
            ));
    }
}
