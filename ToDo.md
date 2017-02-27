ToDo
====

1. Remover dependencias fuertes de Cmf por falta de soporte

2. Crear simple mecanismo para obtener desde un Media el filename

3. Crear simple mecanismo para obtener desde un Media las minuaturas en filename

4. Probar que se muestre apropiadamente la minuatura requerida en el browser

e.g.

    ```
    public function getThumbnails($path, $filter)
    {
        $cacheManager = $this->get('liip_imagine.cache.manager');
        $dataManager = $this->get('liip_imagine.data.manager');
        $filterManager = $this->get('liip_imagine.filter.manager');

        if (!$cacheManager->isStored($path, $filter)) {
            $binary = $dataManager->find($filter, $path);

            $cacheManager->store(
                $filterManager->applyFilter($binary, $filter),
                $path,
                $filter
            );
        }

        $cachePath = $this->container->getParameter('kernel.root_dir').'/../web/media/cache/';

        return $cachePath.$filter.'/'.$path;
    }
    ```

5. Soporte para recoger propiedades de las imágenes:

e.g. con php_gd

if (!$extension) {
            $extension = strtolower(str_replace('.', '', strrchr($filename, '.')));
        }

        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $type = 'image/jpeg';
                $resource = @imagecreatefromjpeg($filename);
                break;
            case 'gif':
                $type = 'image/gir';
                $resource = @imagecreatefromgif($filename);
                break;
            case 'png':
                $type = 'image/png';
                $resource = @imagecreatefrompng($filename);
                break;
            default:
                $type = '';
                $resource = null;
                break;
        }
        $width = imagesx($resource);
        $height = imagesy($resource);
