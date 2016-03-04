<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmMediaBundle\Form\Type;

use Positibe\Bundle\OrmMediaBundle\Form\DataTransformer\ProviderDataTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * Class ImageType
 * @package Positibe\Bundle\OrmMediaBundle\Form\Type
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MediaType extends AbstractType
{
    private $class;

    /**
     * @param $class
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new ProviderDataTransformer($this->class, array(
                    'provider'      => $options['provider'],
                    'empty_on_new'  => $options['empty_on_new'],
                    'new_on_update' => $options['new_on_update'],
                )));

        $builder->addEventListener(FormEvents::SUBMIT, function(FormEvent $event) {
                if ($event->getForm()->get('unlink')->getData()) {
                    $event->setData(null);
                }
            });

        $builder->add('binaryContent', 'file', array(
                'required' => false
            ));

        $builder->add('unlink', 'checkbox', array(
                'mapped'   => false,
                'data'     => false,
                'required' => false
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => $this->class,
                'provider' => null,
                'empty_on_new' => true,
                'new_on_update' => true,
            )
        );
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'positibe_media_type';
    }

} 