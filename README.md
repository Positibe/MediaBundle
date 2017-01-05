PositibeMediaBundle
===================

This bundle provide entities for Symfony-Cmf MediaBundle.

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
            new Symfony\Cmf\Bundle\MediaBundle\CmfMediaBundle(),
            new Positibe\Bundle\MediaBundle\PositibeMediaBundle(),

            // ...
        );
    }

Configuration
-------------

Some `form_themes` are available in twig.
    # app/config/config.yml
    twig:
        form_themes:
            - 'PositibeMediaBundle::_media_type.html.twig'

Add the public routes for the image's download.
    # app/config/routing.yml

    # ...
    positibe_media:
        resource: "@PositibeMediaBundle/Resources/config/routing.yml"

If they are not already created, you need to create some specific folder to allow uploading:

    [bash]
    mkdir web/uploads
    mkdir web/uploads/media
    chmod -R 0777 web/uploads
    mkdir web/media
    mkdir web/media/cache
    chmod -R 0777 web/media

You can also integrate it with Sonata MediaBundle. See its documentation on [http://sonata-project.org/bundles/media/master/doc/index.html](http://sonata-project.org/bundles/media/master/doc/index.html) and [Symfony CmfMediaBundle documentation](http://symfony.com/doc/master/cmf/bundles/media/index.html)

Documentation
-------------

A simple example using Media entity:

    [php]
    $media = new Media();
    $media->setBinaryContent(__DIR__ . '/../Resources/public/img/positibelabs_logotipo 2.jpg');
    $manager->persist($media);
    $manager->flush();

**Note:** You have to set createAt and updateAt properties by yourself if you don't use the timestampable gedmo doctrine extension in your project

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



ToDo
----

Document how to create a Media from binaryContent and how to create, assign a Media and show a Gallery.
