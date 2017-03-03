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
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class MediaCollectionType
 * @package Positibe\Bundle\MediaBundle\Form\Type
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MediaCollectionType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['provider' => MediaProviderInterface::MEDIA_PROVIDER]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return GalleryType::class;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'positibe_media_collection';
    }

} 