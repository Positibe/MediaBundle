<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MediaBundle\Helper;

use Positibe\Bundle\MediaBundle\Model\FileInterface;
use Positibe\Bundle\MediaBundle\Model\MediaManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class UploadCkeditorHelper
{
    protected $mediaManager;
    protected $router;
    protected $propertyMapping;
    /**
     * Instantiate.
     *
     * The $propertyMapping is an array that tells what request parameters go
     * into which document properties. You could map for example "caption" to
     * the "description" field with `array('caption' => 'description')`.
     *
     * @param MediaManagerInterface $mediaManager
     * @param RouterInterface       $router
     * @param array                 $propertyMapping maps request parameters to
     *                                               Media document properties.
     */
    public function __construct(MediaManagerInterface $mediaManager, RouterInterface $router, array $propertyMapping = array())
    {
        $this->mediaManager = $mediaManager;
        $this->router = $router;
        $this->propertyMapping = $propertyMapping;
    }

    /**
     * {@inheritdoc}
     */
    public function setFileDefaults(Request $request, FileInterface $file)
    {
        // map request parameters to Media properties
        foreach ($this->propertyMapping as $param => $property) {
            if (strlen($request->get($param))) {
                $setter = 'set'.ucfirst($property);
                $file->$setter($request->get($param));
            }
        }
    }
    /**
     * {@inheritdoc}
     */
    public function getUploadResponse(Request $request, array $files)
    {
        if (!isset($files[0]) && !$files[0] instanceof FileInterface) {
            throw new \InvalidArgumentException(
                'Provide at least one Positibe\Bundle\MediaBundle\Model\FileInterface file.'
            );
        }

        $urlSafePath = $this->mediaManager->getUrlSafePath($files[0]);
        $url = $this->router->generate('positibe_media_image_display', array('path' => $urlSafePath));
        $funcNum = $request->query->get('CKEditorFuncNum');

        $data = "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction(".$funcNum.", '".$url."', 'success');</script>";

        $response = new Response($data);
        $response->headers->set('Content-Type', 'text/html');

        return $response;
    }
}
