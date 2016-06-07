<?php

namespace BackendBundle\Repository;
use Doctrine\ORM\EntityRepository;

/**
 * DealRepository
 *
 * Custom repository class for the Deal entity, wraps the basic doctrine operations
 * as well as custom methods from the domain.
 *
 * @package \BackendBundle\Repository
 */
class DealRepository extends EntityRepository
{
    /**
     * Returns the top N deals based on the creation date, if no parameter (or zero)
     * is specified the top 3 deals are returned
     *
     * @param int $n
     *
     * @return \Doctrine\ORM\Query
     */
    public function findTopDeals($n = 0)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT d FROM BackendBundle:Deal d ORDER BY d.createdAt')
            ->setMaxResults($n !== 0 ? $n : 3)
            ->getResult()
        ;
    }
}