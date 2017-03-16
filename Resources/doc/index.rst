Positibe MediaBundle
====================

A simple example using ``Media`` entity:

.. code-block:: php

    <?php
    $media = new Media();
    $media->setBinaryContent(__DIR__ . '/../Resources/public/img/positibelabs_logotipo 2.jpg');
    $manager->persist($media);
    $manager->flush();

A simple example using ``Gallery`` entity:

.. code-block:: php

    <?php
    $media = new Media();
    $media->setBinaryContent(__DIR__.'/../../Resources/public/img/positibelabs_logotipo 2.jpg');

    $gallery = new Gallery();

    $gallery->setName('PositibeLabs Gallery'); //This step is not necessary. Default name is `gallery`
    $gallery->setDefaultFormat('.zip'); //This step is not necessary. Default format is `.jpg`

    //You can optional also set title and body content to each media in the gallery
    $gallery->addMedia($media, 'PositibeLabs', 'PositibeLabs is developing great open-source libraries');

    $manager->persist($gallery);
    $manager->flush();

Form types
----------

A simple example using ``MediaType``, ``ImageType``, ``GalleryType``, ``MediaCollectionType`` types:

.. code-block:: php

    <?php
    namespace AppBundle\Form\Type;

    use Positibe\Bundle\MediaBundle\Form\Type\GalleryType;
    use Positibe\Bundle\MediaBundle\Form\Type\ImageType;
    use Positibe\Bundle\MediaBundle\Form\Type\MediaCollectionType;
    use Positibe\Bundle\MediaBundle\Form\Type\MediaType;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    /**
     * Class UserFormType
     * @package AppBundle\Form\Type
     */
    class UserFormType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('username')
                ->add('email')
                ->add('avatar', ImageType::class) //Image
                ->add('media', MediaType::class) //Any file
                ->add('gallery', GalleryType::class) //Sets of images
                ->add('media_collection', MediaCollectionType::class) //Sets of any files
                ;
        }

        /**
         * @param OptionsResolver $resolver
         */
        public function setDefaultOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults(
                array(
                    'data_class' => 'AppBundle\Entity\User',
                )
            );
        }
    }

To have a good view of your ``input type="file"`` you must add in your html style and javascript the follows lines:

.. code-block:: html

    <!DOCTYPE html>
    <head>
        <!-- ... More links with bootstrap .. -->
        <link href="{{ asset('bundles/positibemedia/jquery-file-upload/css/file-upload.all.min.css') }}" rel="stylesheet">
        <link href="{{ asset('bundles/positibemedia/css/media.css') }}" rel="stylesheet">

        <!--- ... Customs styles ... -->
        {% block stylesheets %}{% endblock %}
    </head>
    <body>
        <!-- ... All contents and twigs blocks -->
        <!-- ... Your Jquery and Bootstrap javascripts -->
        <script src="{{ asset('bundles/positibemedia/bootstrap-fileupload/bootstrap-fileupload.js') }}" type="application/javascript"></script>
        <!-- ... Your custom and init functions -->
    </body>

Twig functions
--------------

Display function
~~~~~~~~~~~~~~~~

You can use ``display_image`` to display an image:


.. code-block:: jinja

    <img src="{{ display_image(media, {'imagine_filter':'image_thumbnail', 'default': asset('bundles/positibemedia/images/avatar.png')}) }}"
         alt="{{ media }}">

Download function
~~~~~~~~~~~~~~~~~

You can use ``download_file`` to download a file:


.. code-block:: jinja

    <a href="{{ download_file(media) }}" class="btn btn-info">
        <i class="fa fa-download"></i> Descargar
    </a>

Gallery example
~~~~~~~~~~~~~~~

.. code-block:: jinja

    {% for media in user.gallery.galleryHasMedias %}
        <img class="rounded-circle img-responsive"
             src="{{ display_image(media, {'imagine_filter':'image_thumbnail', 'default': asset('bundles/positibemedia/images/avatar.png')}) }}"
             alt="{{ media }}">
    {% endfor %}
    {% for media in user.gallery.galleryHasMedias %}
        <a href="{{ download_file(media, {'imagine_filter':'image_thumbnail', 'default': asset('bundles/positibemedia/images/avatar.png')}) }}"
           title="{{ media }}">{{ media }}</a>
    {% endfor %}

Filesystem function
-------------------

How to obtain the filename of a Media
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: php

    <?php
    $media = // $mediaManager->find(1);

    $filename = $this->get('positibe_media.media_manager')->getFilename($media);

How to get the filter's filename of a Media
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: php

    <?php
    $media = // $mediaManager->find(1);

    $filesystemResolver = $this->get('positibe_media.filesystem_resolver');

    $mediaOriginalPath = $filsystemResolver->resolve($media->getPath()); //To get the original filename same MediaManager->getFilename($media)
    $mediaFilteredPath = $filsystemResolver->resolve($media->getPath(), 'image_thumbnail'); //To get the filtered filename

How to extract extension, mime type and some information
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: php

    <?php
    //Get the filename of any file
    $filename = $filesystemResolver->resolve('bundles/positibemedia/images/photo.png');

    $mime = $this->get('liip_imagine.mime_type_guesser')->guess($filename); //Get the mime type from filename

    $extension = $this->get('liip_imagine.extension_guesser')->guess($mime); //Get the extension from a mime type

    switch ($mime) {
        case 'image/jpeg':
            $resource = @imagecreatefromjpeg($filename);
            break;
        case 'image/gif':
            $resource = @imagecreatefromgif($filename);
            break;
        case 'image/png':
            $resource = @imagecreatefrompng($filename);
            break;
    }

    $width = imagesx($resource); //Get the width
    $height = imagesy($resource); //Get the height

** Warning: ** By some matter .ico file doesn't work.

Using CKEditor Helper
---------------------

The helpers are including by default in the ``service_container``. But before you star using it you must
ensure yours editor users have the ``ROLE_EDITOR`` role.

.. code-block:: yaml

    security:
        role_hierarchy:
            ROLE_ADMIN:       [ROLE_USER, ROLE_EDITOR]
            ROLE_SUPER_ADMIN: ROLE_ADMIN