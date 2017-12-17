<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/**
 * Class FileController
 * @package Positibe\Bundle\MediaBundle\Controller
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class FileController extends Controller
{
    public function downloadAction($path)
    {
        $mediaManager = $this->container->get('positibe_media.media_manager');
        try {
            $media = $mediaManager->getMediaByPath($path);
        } catch (\OutOfBoundsException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        if ($filename = $mediaManager->getFilename($media)) {
            $response = new BinaryFileResponse($filename);
            $response->headers->set('Content-Type', $media->getContentType());
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $media->getName());
        } else {
            $response = new Response(
                $this->container->get('positibe_media.local_gaufrette_adapter')->read($media->getContentAsString())
            );
            $response->headers->set('Content-Type', $media->getContentType());
            $response->headers->set('Content-Length', $media->getSize());
            $response->headers->set('Content-Transfer-Encoding', 'binary');

            $disposition = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                $media->getName()
            );
            $response->headers->set('Content-Disposition', $disposition);
        }

        return $response;
    }

    /**
     * Action to display an image object that has a route
     *
     * @param $path
     * @return Response
     */
    public function displayAction($path)
    {
        $mediaManager = $this->container->get('positibe_media.media_manager');
        try {
            if ($media = $mediaManager->getMediaByPath($path)) {
                $response = new Response(
                    $this->container->get('positibe_media.local_gaufrette_adapter')->read(
                        $media->getContentAsString()
                    )
                );
                $response->headers->set('Content-Type', $media->getContentType());

                return $response;
            } elseif ($media = $mediaManager->getMediaByPreviewPath($path)) {
                $file = $this->container->get('positibe_media.local_gaufrette_adapter')->read(
                    $media->getPreview()
                );
                $response = new Response($file);
                $response->headers->set('Content-Type', 'image');

                return $response;
            }

        } catch (\OutOfBoundsException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        throw new NotFoundHttpException();
    }

    /**
     * Action to upload a file
     *
     * @param  Request $request
     * @return Response
     */
    public function uploadAction(Request $request)
    {
        $this->checkSecurityUpload();

        return $this->container->get('positibe_media.upload_file_helper')->getUploadResponse($request);
    }

    /**
     * Decide whether the user is allowed to upload a file.
     *
     * @throws AccessDeniedException if the current user is not allowed to
     *                               upload.
     *
     */
    protected function checkSecurityUpload()
    {
        if ($this->container->get('security.authorization_checker')->isGranted(
            $this->container->getParameter('positibe_media.upload_file_role')
        )
        ) {
            return;
        }

        throw new AccessDeniedException();
    }
} 