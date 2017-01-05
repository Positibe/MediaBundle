<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MediaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class GalleryType
 * @package Positibe\Bundle\MediaBundle\Form\Type
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class GalleryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'gallery_has_medias',
                CollectionType::class,
                array(
                    'label' => 'gallery.form.gallery_has_medias',
                    'by_reference' => false,
                    'type' => 'Positibe\Bundle\MediaBundle\Form\Type\GalleryHasMediaType',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'options' => array(
                        'required' => false,
                        'provider' => $options['provider'],
                    ),
                    'required' => false,
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Positibe\Bundle\MediaBundle\Entity\Gallery',
                'provider' => 'positibe_media.image_provider',
            )
        );

        $resolver->addAllowedValues(
            'provider', ['positibe_media.image_provider', 'positibe_media.media_provider']
        );

    }
    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'positibe_gallery';
    }

} 