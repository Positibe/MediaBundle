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

use Positibe\Bundle\MediaBundle\Entity\Media;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MediaController
 * @package Positibe\Bundle\MediaBundle\Controller
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MediaController extends Controller
{
    public function indexAction(Request $request)
    {
        $manager = $this->get('doctrine.orm.entity_manager');
        $medias = $this->get('doctrine.orm.entity_manager')->getRepository(
            'PositibeMediaBundle:Media'
        )->createPagination($request->query->get('criteria', []), $request->query->get('sorting', []));
        $medias->setMaxPerPage($request->query->get('limit', 30));
        $medias->setCurrentPage($request->query->get('page', 1));

        // This prevents Pagerfanta from querying database from a template
        $medias->getCurrentPageResults();

        $mimeTypes = $manager->createQueryBuilder()->select('DISTINCT(o.contentType) AS contentType')->from(
            'PositibeMediaBundle:Media',
            'o'
        )->getQuery()->getArrayResult();

        return $this->render(
            'PositibeMediaBundle:Media:index.html.twig',
            ['medias' => $medias, 'mimeTypes' => $mimeTypes]
        );
    }

    public function updateAction(Request $request, Media $media)
    {
        $form = $this->createForm($this->get($media->getProviderName())->getFormTypeClass(), $media);
        $form->add('name', null, ['label' => 'Nombre']);
        if ($request->isMethod('PUT')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $manager = $this->get('doctrine.orm.entity_manager');
                $media->setUpdatedAt(new \DateTime());
                $manager->persist($media);
                $manager->flush();

                return $this->redirectToRoute('positibe_media_update', ['id' => $media->getId()]);
            }
        }

        return $this->render(
            'PositibeMediaBundle:Media:update.html.twig',
            ['media' => $media, 'form' => $form->createView()]
        );
    }
}