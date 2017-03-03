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

use Positibe\Bundle\MediaBundle\Provider\MediaProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class GalleryHasMediaType
 * @package Positibe\Bundle\MediaBundle\Form\Type
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class GalleryHasMediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'media',
                $options['provider'] === MediaProviderInterface::IMAGE_PROVIDER ? ImageType::class : MediaType::class,
                array(
                    'label' => 'gallery_has_media.form.media',
                    'provider' => $options['provider'],
                )
            )
            ->add(
                'title',
                null,
                array(
                    'label' => 'gallery_has_media.form.title'
                )
            )
            ->add(
                'body',
                null,
                array(
                    'label' => 'gallery_has_media.form.body',
                )
            )
            ->add(
                'position',
                null,
                array(
                    'label' => 'gallery_has_media.form.position',
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Positibe\Bundle\MediaBundle\Entity\GalleryHasMedia',
                'translation_domain' => 'PositibeMediaBundle',
                'provider' => MediaProviderInterface::IMAGE_PROVIDER,
            )
        );

        $resolver->addAllowedValues(
            'provider',
            [MediaProviderInterface::IMAGE_PROVIDER, MediaProviderInterface::MEDIA_PROVIDER]
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'positibe_gallery_has_media';
    }
} 