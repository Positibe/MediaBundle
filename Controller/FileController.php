<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmMediaBundle\Controller;

use Positibe\Bundle\OrmMediaBundle\Entity\AbstractMedia;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/**
 * Class FileController
 * @package Positibe\Bundle\OrmMediaBundle\Controller
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class FileController extends Controller
{
    public function downloadAction($path)
    {
        /** @var AbstractMedia $contentDocument */
        $contentDocument = $this->container->get('doctrine.orm.entity_manager')->getRepository($this->container->getParameter('positibe_orm_media.image.class'))->findOneBy(array(
                'path' => $path
            ));
        $file = $this->container->getParameter('positibe_orm_media.filesystem_path') . $contentDocument->getPath();

        if ($file) {
            $response = new BinaryFileResponse($file);
            $response->headers->set('Content-Type', $contentDocument->getContentType());
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $contentDocument->getName());
        } else {
            $response = new Response($contentDocument->getContentAsString());
            $response->headers->set('Content-Type', $contentDocument->getContentType());
            $response->headers->set('Content-Length', $contentDocument->getSize());
            $response->headers->set('Content-Transfer-Encoding', 'binary');

            $disposition = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                $contentDocument->getName()
            );
            $response->headers->set('Content-Disposition', $disposition);
        }

        return $response;
    }

    /**
     * Action to upload a file
     *
     * @param  Request  $request
     * @return Response
     */
    public function uploadAction(Request $request)
    {
        $this->checkSecurityUpload($request);

        return $this->container->get('positibe_orm_media.upload_file_helper')->getUploadResponse($request);
    }

    /**
     * Decide whether the user is allowed to upload a file.
     *
     * @throws AccessDeniedException if the current user is not allowed to
     *                               upload.
     *
     * @param Request $request
     */
    protected function checkSecurityUpload(Request $request)
    {
        $securityContext = $this->container->get('security.context');

        if ($securityContext
            && $securityContext->getToken()
            && $securityContext->isGranted($this->container->getParameter('cmf_media.upload_file_role'))
        ) {
            return;
        }

        throw new AccessDeniedException();
    }
} 