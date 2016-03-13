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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * Class GalleryHasMediaType
 * @package Positibe\Bundle\OrmMediaBundle\Form\Type
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
                'positibe_image_type',
                array(
                    'label' => 'gallery_has_media.form.media',
                    'provider' => 'positibe_orm_media.image_provider'
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
                    'label' => 'gallery_has_media.form.body'
                )
            );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Positibe\Bundle\OrmMediaBundle\Entity\GalleryHasMedia',
                'translation_domain' => 'PositibeOrmMediaBundle'
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
        return 'positibe_gallery_has_media_type';
    }

} 