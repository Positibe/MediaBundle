<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmMediaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Positibe\Bundle\OrmBlockBundle\Entity\Block;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table("positibe_block_media")
 * @ORM\Entity(repositoryClass="Positibe\Bundle\OrmBlockBundle\Entity\MediaBlockRepository")
 *
 * Class MediaBlock
 * @package Positibe\Bundle\OrmMediaBundle\Entity
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MediaBlock extends Block
{

    /**
     * @var Media[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Positibe\Bundle\OrmMediaBundle\Entity\Media", cascade="all")
     * @ORM\JoinTable(name="positibe_block_media_medias")
     */
    private $medias;

    public function __construct()
    {
        parent::__construct();
        $this->medias = new ArrayCollection();
        $this->type = 'positibe_orm_media.block_media';
    }

    public function getType()
    {
        return 'positibe_orm_media.block_media';
    }

    /**
     * @return ArrayCollection|\Positibe\Bundle\OrmMediaBundle\Entity\Media[]
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * @param ArrayCollection|\Positibe\Bundle\OrmMediaBundle\Entity\Media[] $medias
     */
    public function setMedias($medias)
    {
        $this->medias = $medias;
    }

    /**
     * @param ArrayCollection|\Positibe\Bundle\OrmMediaBundle\Entity\Media[] $media
     * @return $this
     */
    public function addMedia($media)
    {
        $this->medias->add($media);

        return $this;
    }

    /**
     * @param ArrayCollection|\Positibe\Bundle\OrmMediaBundle\Entity\Media[] $media
     * @return $this
     */
    public function removeMedia($media)
    {
        $this->medias->removeElement($media);

        return $this;
    }
} 