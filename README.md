PositibeMediaBundle
===================

This bundle provides entities for Symfony-Cmf MediaBundle.

Installation
------------

To install the bundle just add the dependent bundles:

    php composer.phar require positibe/media-bundle

Next, be sure to enable the bundles in your application kernel:

    <?php
    // app/AppKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            new Liip\ImagineBundle\LiipImagineBundle(),
            new Positibe\Bundle\MediaBundle\PositibeMediaBundle(),
            // ...
        );
    }

Configuration
-------------

Load all basic configuration:

    # app/config/config.yml
    imports:
        # ...
        - { resource: '@PositibeMediaBundle/Resources/config/config.yml'}

Or you can set those configurations by you own:

    # app/config/config.yml
    parameters:
        positibe_media.media.class: 'Positibe\Bundle\MediaBundle\Entity\Media'
        positibe_media.url_path: 'uploads/media'
        positibe_media.web_root: %kernel.root_dir%/../web
        positibe_media.cache_prefix: media/cache

    twig:
        form_themes:
            - 'PositibeMediaBundle::_media_type.html.twig'

    liip_imagine:
        resolvers:
            default:
                web_path:
                    web_root: %positibe_media.web_root%
                    cache_prefix: %positibe_media.cache_prefix%
        filter_sets:
            # define the filter to be used with the image preview
            image_upload_thumbnail:
                filters:
                    thumbnail: { size: [100, 100], mode: outbound }
            image_thumbnail:
                filters:
                    thumbnail: { size: [250, 250], mode: outbound }
            image_thumbnail_small:
                filters:
                    thumbnail: { size: [50, 50], mode: outbound }

Add the public routes for the image's download.
    # app/config/routing.yml

    # ...
    positibe_media:
        resource: "@PositibeMediaBundle/Resources/config/routing.yml"

** Note:** If you are using the Ckeditor you have to set the role ``ROLE_EDITOR`` to that users who can upload images

If they are not already created, you need to create some specific folder to allow uploading:

    [bash]
    mkdir web/uploads
    mkdir web/uploads/media
    chmod -R 0777 web/uploads
    mkdir web/media
    mkdir web/media/cache
    chmod -R 0777 web/media

**Note:** You must configure some Gedmo Doctrine Extension timestampable, sortable, translatable

Documentation
-------------

Documentation is available [here](Resources/doc/index.rst).

A simple example using Media entity:

    [php]
    $media = new Media();
    $media->setBinaryContent(__DIR__ . '/../Resources/public/img/positibelabs_logotipo 2.jpg');
    $manager->persist($media);
    $manager->flush();

A simple example using Gallery entity:

    [php]
    $media = new Media();
    $media->setBinaryContent(__DIR__.'/../../Resources/public/img/positibelabs_logotipo 2.jpg');

    $gallery = new Gallery();

    $gallery->setName('PositibeLabs Gallery'); //This step is not necessary. Default name is `gallery`
    $gallery->setDefaultFormat('.zip'); //This step is not necessary. Default format is `.jpg`

    //You can optional also set title and body content to each media in the gallery
    $gallery->addMedia($media, 'PositibeLabs', 'PositibeLabs is developing great open-source libraries');

    $manager->persist($gallery);
    $manager->flush();

