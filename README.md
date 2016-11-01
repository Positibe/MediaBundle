PositibeMediaBundle
======================

This bundle provide a ORM entities to use Symfony Cmf MediaBundle.

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

For more information see the Sonata MediaBundle documentation on [http://sonata-project.org/bundles/media/master/doc/index.html](http://sonata-project.org/bundles/media/master/doc/index.html) and [Symfony CmfMediaBundle documentation](http://symfony.com/doc/master/cmf/bundles/media/index.html)