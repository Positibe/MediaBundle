<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmMediaBundle\Block;

use Positibe\Bundle\OrmBlockBundle\Block\Service\AbstractBlockService;


/**
 * Class SlideshowBlockService
 * @package Positibe\Bundle\OrmMediaBundle\Block
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class SlideshowBlockService extends AbstractBlockService
{
    protected $template = 'PositibeOrmMediaBundle:Block:block_slideshow.html.twig';
} 