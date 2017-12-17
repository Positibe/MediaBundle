<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MediaBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * Class MediaRepository
 * @package Positibe\Bundle\MediaBundle\Repository
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MediaRepository extends EntityRepository
{
    /**
     * @param array $criteria
     * @param array $sorting
     * @return Pagerfanta
     */
    public function createPagination($criteria = [], $sorting = [])
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $this->applyCriteria($queryBuilder, $criteria);
        $this->applySorting($queryBuilder, $sorting);

        return new Pagerfanta(new DoctrineORMAdapter($queryBuilder, false, false));
    }

    public function applyCriteria(QueryBuilder $queryBuilder, $criteria = [])
    {
        foreach ($criteria as $property => $value) {
            if (!in_array($property, $this->_class->getFieldNames())) {
                continue;
            }

            $name = $this->getPropertyName($property);

            if (null === $value) {
                $queryBuilder->andWhere($queryBuilder->expr()->isNull($name));
            } elseif (is_array($value)) {
                $orNull = false;

                for ($i = 0; $i < count($value); $i++) {
                    if ($value[$i] === null) {
                        unset($value[$i]);
                        $orNull = true;
                    }
                }

                if (count($value) > 0) {
                    if ($orNull) {
                        $queryBuilder->andWhere(
                            $queryBuilder->expr()->orX(
                                $queryBuilder->expr()->in($name, $value),
                                $orNull ? $queryBuilder->expr()->isNull($name) : null
                            )
                        );
                    } else {
                        $queryBuilder->andWhere($queryBuilder->expr()->in($name, $value));
                    }
                    continue;
                }

                if ($orNull) {
                    $queryBuilder->andWhere($queryBuilder->expr()->isNull($name));
                }
            } elseif ('' !== $value) {
                $parameter = str_replace('.', '_', $property);
                $mapping = $this->_class->getFieldMapping($property);
                if ($mapping['type'] === 'string' || $mapping['type'] === 'text') {
                    $queryBuilder->andWhere($name.' LIKE :'.$parameter)->setParameter($parameter, '%'.$value.'%');
                } else {
                    $queryBuilder
                        ->andWhere($queryBuilder->expr()->eq($name, ':'.$parameter))
                        ->setParameter($parameter, $value);
                }
            }
        }
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param array $sorting
     */
    protected function applySorting(QueryBuilder $queryBuilder, array $sorting = [])
    {
        foreach ($sorting as $property => $order) {
            if (!in_array(
                $property,
                array_merge($this->_class->getAssociationNames(), $this->_class->getFieldNames())
            )
            ) {
                continue;
            }

            if (!empty($order)) {
                $queryBuilder->addOrderBy($this->getPropertyName($property), $order);
            }
        }
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getPropertyName($name)
    {
        if (false === strpos($name, '.')) {
            return 'o'.'.'.$name;
        }

        return $name;
    }
}