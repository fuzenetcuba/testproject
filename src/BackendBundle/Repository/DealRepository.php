<?php

namespace BackendBundle\Repository;
use Doctrine\ORM\EntityRepository;
use Psr\Log\InvalidArgumentException;

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
     * Returns the most recent N deals based on the creation date, if no parameter (or zero)
     * is specified the top 3 deals are returned
     *
     * @param int $n
     *
     * @return \Doctrine\ORM\Query
     */
    public function findRecentDeals($n = 3)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT d FROM BackendBundle:Deal d ORDER BY d.createdAt DESC')
            ->setMaxResults($n !== 0 ? $n : 3)
            ->getResult()
        ;
    }

    /**
     * Returns all deals until a given date, the given date should be expressed in a
     * string with a valid format to parse by DateTime
     *
     * @param string $strDate
     *
     * @return \Doctrine\ORM\AbstractQuery
     */
    public function findDealUntil($strDate = 'NOW')
    {
        return $this->getEntityManager()
            ->createQuery('SELECT d FROM BackendBundle:Deal d WHERE d.endsAt <= :param')
            ->setParameter('param', new \DateTime($strDate))
            ->getResult()
        ;
    }
}