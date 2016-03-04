PositibeOrmMediaBundle
======================

This bundle provide a ORM entities to use Symfony Cmf MediaBundle.

Installation
------------

To install the bundle just add the dependent bundles:

    php composer.phar require positibe/orm-media-bundle

Next, be sure to enable the bundles in your application kernel:

    <?php
    // app/AppKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            // Dependency (check that you don't already have this line)
            new Symfony\Cmf\Bundle\CoreBundle\CmfCoreBundle(),
            // Vendor specifics bundles
            new Liip\ImagineBundle\LiipImagineBundle(),
            new Symfony\Cmf\Bundle\MediaBundle\CmfMediaBundle(),
            new Positibe\Bundle\OrmMediaBundle\PositibeOrmMediaBundle(),

            // ...
        );
    }

Configuration
-------------

Import all necessary configurations to your app/config/config.yml the basic configuration.
    # app/config/config.yml
    imports:
        - { resource: @PositibeOrmMediaBundle/Resources/config/config.yml }

Agrega las rutas públicas para la descarga de las imágenes

    # app/config/routing.yml

    # ...
    positibe_orm_media:
        resource: "@PositibeOrmMediaBundle/Resources/config/routing.yml"

If they are not already created, you need to add specific folder to allow uploads from users:

    [bash]
    mkdir web/uploads
    mkdir web/uploads/media
    chmod -R 0777 web/uploads

For more information see the Sonata MediaBundle documentation on [http://sonata-project.org/bundles/media/master/doc/index.html](http://sonata-project.org/bundles/media/master/doc/index.html) and [Symfony CmfMediaBundle documentation](http://symfony.com/doc/master/cmf/bundles/media/index.html)