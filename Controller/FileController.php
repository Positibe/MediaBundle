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

use Gaufrette\Adapter\Local;
use Positibe\Bundle\MediaBundle\Helper\UploadFileHelper;
use Positibe\Bundle\MediaBundle\Model\MediaManager;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/**
 * Class FileController
 * @package Positibe\Bundle\MediaBundle\Controller
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class FileController
{
    /**
     * @param MediaManager $mediaManager
     * @param Local $localAdapter
     * @param $path
     * @return BinaryFileResponse|Response
     */
    public function downloadAction(MediaManager $mediaManager, Local $localAdapter, $path)
    {
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
                $localAdapter->read($media->getContentAsString())
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
     * @deprecated It's not needed on filesystem files
     *
     * Action to display an image object that has a route
     *
     * @param MediaManager $mediaManager
     * @param Local $localAdapter
     * @param $path
     * @return Response
     */
    public function displayAction(MediaManager $mediaManager, Local $localAdapter, $path)
    {
        try {
            if ($media = $mediaManager->getMediaByPath($path)) {
                $response = new Response(
                    $localAdapter->read(
                        $media->getContentAsString()
                    )
                );
                $response->headers->set('Content-Type', $media->getContentType());

                return $response;
            } elseif ($media = $mediaManager->getMediaByPreviewPath($path)) {
                $file = $localAdapter->read(
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
     * @param UploadFileHelper $uploadFileHelper
     * @param Request $request
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param $uploadFileRole
     * @return mixed
     */
    public function uploadAction(
        UploadFileHelper $uploadFileHelper,
        Request $request,
        AuthorizationCheckerInterface $authorizationChecker,
        $uploadFileRole
    ) {
        // Decide whether the user is allowed to upload a file.
        if (!$authorizationChecker->isGranted($uploadFileRole)) {
            throw new AccessDeniedException();
        }


        return $uploadFileHelper->getUploadResponse($request);
    }
} 