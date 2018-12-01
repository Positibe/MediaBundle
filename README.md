PositibeMediaBundle
===================

This bundle provides entities for Symfony-Cmf MediaBundle.

Installation
------------

To install the bundle just add the dependent bundles:

    php composer.phar require positibe/media-bundle

Next, be sure to enable the bundles in your application kernel:

    <?php
    // config/kernel.php
    return [
        // ...
        Liip\ImagineBundle\LiipImagineBundle::class => ['all' => true],
        Positibe\Bundle\MediaBundle\PositibeMediaBundle::class => ['all' => true],
    ];


Configuration
-------------

Create your configuration file:

    # config/packages/positibe_media.yml
    parameters:
        positibe_media.media_class: 'Positibe\Bundle\MediaBundle\Entity\Media'
        positibe_media.url_path: 'uploads/media'
        positibe_media.web_root: "%kernel.root_dir%/../public/"
        positibe_media.cache_prefix: media/cache
        positibe_media.upload_file_role: ROLE_EDITOR

    twig:
        form_themes:
            - 'PositibeMediaBundle::_media_type.html.twig'
    
    liip_imagine:
        resolvers:
            default:
                web_path:
                    web_root: "%positibe_media.web_root%"
                    cache_prefix: "%positibe_media.cache_prefix%"
        loaders:
            default:
                filesystem:
                    data_root: "%positibe_media.web_root%"  # %kernel.root_dir%/../web/
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
            image_preview:
                filters:
                    thumbnail: { size: [190, 190], mode: outbound }

Create your own routing for managing medias

    [yaml]
    # config/routing/positibe_media.yaml
    positibe_media_image_display:
        path: /media/display/{path}
        defaults: { _controller: PositibeMediaBundle:File:display }
        requirements:
            method: GET
            path: .*
    
    positibe_media_download:
        path: /media/download/{path}
        defaults: { _controller: PositibeMediaBundle:File:download }
        requirements:
            method: GET
            path: .*
    
    positibe_media_upload:
        path: /media/image/upload/{editor}
        defaults: { _controller: PositibeMediaBundle:File:upload, editor: ckeditor, _format: json }
        requirements:
            method: POST
    
    _liip_imagine:
       resource: "@LiipImagineBundle/Resources/config/routing.yaml"

To integrate Positibe Management

    [yaml]
     # config/routing/admin/positibe_media.yaml
    positibe_media_update:
        path: /multimedias/{id}
        methods: [GET, PUT]
        defaults: { _controller: PositibeMediaBundle:Media:update }
    
    positibe_media_delete:
        path: /multimedias/{id}
        methods: [DELETE]
        defaults: { _controller: PositibeMediaBundle:Media:update }
    
    positibe_media_index:
        path: /multimedias
        defaults: { _controller: PositibeMediaBundle:Media:index }

** Note:** If you are using the Ckeditor you have to set the role ``ROLE_EDITOR`` to that users who can upload images

If they are not already created, you need to create some specific folder to allow uploading:

    [bash]
    mkdir public/uploads
    mkdir public/uploads/media
    chmod -R 0777 public/uploads
    mkdir public/media
    mkdir public/media/cache
    chmod -R 0777 public/media

**Note:** You must configure some Gedmo Doctrine Extension timestampable, sortable, translatable

Documentation
-------------

Documentation is available [here](Resources/doc/index.rst).

A simple example using Media entity:

    [php]
    //$media = new Media(); //Default provider MediaProvider::getName()
    $media = new Media(ImageProvider::getName()); //Image provider
    $media->setBinaryContent(__DIR__ . '/../Resources/public/img/positibelabs_logotipo 2.jpg');
    $manager->persist($media);
    $manager->flush();

A simple example using Gallery entity:

    [php]
    $media = new Media(ImageProvider::getName());
    $media->setBinaryContent(__DIR__.'/../../Resources/public/img/positibelabs_logotipo 2.jpg');

    $gallery = new Gallery();

    $gallery->setName('PositibeLabs Gallery'); //This step is not necessary. Default name is `gallery`
    $gallery->setDefaultFormat('.zip'); //This step is not necessary. Default format is `.jpg`

    //You can optional also set title and body content to each media in the gallery
    $gallery->addMedia($media, 'PositibeLabs', 'PositibeLabs is developing great open-source libraries');

    $manager->persist($gallery);
    $manager->flush();

