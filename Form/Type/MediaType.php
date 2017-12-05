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

use Doctrine\ORM\EntityManager;
use Positibe\Bundle\MediaBundle\Form\DataTransformer\ProviderDataTransformer;
use Positibe\Bundle\MediaBundle\Provider\MediaProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class MediaType
 * @package Positibe\Bundle\MediaBundle\Form\Type
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MediaType extends AbstractType
{
    protected $class;
    protected $em;

    /**
     * @param $class
     */
    public function __construct(EntityManager $entityManager, $class)
    {
        $this->em = $entityManager;
        $this->class = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(
            new ProviderDataTransformer(
                $this->em,
                $this->class,
                [
                    'provider' => $options['provider'],
                    'empty_on_new' => $options['empty_on_new'],
                    'new_on_update' => $options['new_on_update'],
                ]
            )
        );

        $builder->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event) {
                if ($event->getForm()->get('unlink')->getData()) {
                    $media = $event->getData();
                    $this->em->remove($media);
                    $event->setData(null);
                }
            }
        );


        $builder->add(
            'binaryContent',
            'Symfony\Component\Form\Extension\Core\Type\FileType',
            [
                'required' => false,
                'translation_domain' => 'PositibeMediaBundle',
            ]
        );


        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $formEvent) {
                $form = $formEvent->getForm();
                $form->add(
                    'configure',
                    CheckboxType::class,
                    [
                        'mapped' => false,
                        'label' => 'input.configure',
                        'translation_domain' => 'PositibeMediaBundle',
                        'required' => false,
                    ]
                );

                $form->add(
                    'name',
                    TextType::class,
                    [
                        'label' => 'input.name',
                        'required' => false,
                        'translation_domain' => 'PositibeMediaBundle',
                        'attr' => [
                            'class' => 'form-toggle form-toggle-if',
                            'data-toggle_if_for' => $form->getParent()->getName().'_'.$form->getName().'_configure',
                            'data-toggle_if_value' => '1',
                        ],
                    ]
                );

                $form->add(
                    'path',
                    'Symfony\Component\Form\Extension\Core\Type\TextType',
                    [
                        'label' => 'input.path',
                        'required' => false,
                        'translation_domain' => 'PositibeMediaBundle',
                        'attr' => [
                            'class' => 'form-toggle form-toggle-if',
                            'data-toggle_if_for' => $form->getParent()->getName().'_'.$form->getName().'_configure',
                            'data-toggle_if_value' => '1',
                        ],
                    ]
                );
                $form->add(
                    'binaryContentPreview',
                    'Symfony\Component\Form\Extension\Core\Type\FileType',
                    [
                        'required' => false,
                        'translation_domain' => 'PositibeMediaBundle',
                        'attr' => [
                            'class' => 'form-toggle form-toggle-if',
                            'data-toggle_if_for' => $form->getParent()->getName().'_'.$form->getName().'_configure',
                            'data-toggle_if_value' => '1',
                        ],
                    ]
                );
            }
        );

        $builder->add(
            'unlink',
            'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
            [
                'mapped' => false,
                'data' => false,
                'required' => false,
                'label' => 'input.unlink',
                'translation_domain' => 'PositibeMediaBundle',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => $this->class,
                'provider' => MediaProvider::getName(),
                'empty_on_new' => true,
                'new_on_update' => true,
            ]
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'positibe_media';
    }

} 