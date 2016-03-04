<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmMediaBundle\Adapter;

use Gaufrette\Adapter\Local;


/**
 * Class LocalGaufretteAdapter
 * @package Positibe\Bundle\OrmMediaBundle\Adapter
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class LocalGaufretteAdapter extends Local
{
    public function getDirectory()
    {
        return $this->directory;
    }
} 