<?php

namespace BackendBundle\Model;

use BackendBundle\Entity\Deal;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Manager for the Deal entity
 *
 * @package \BackendBundle\Model
 */
class DealManager implements ManagerInterface
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Return the DQL to fetch all model objects
     *
     * @return QueryBuilder
     */
    public function findAllQuery()
    {
        return $this->em
            ->createQueryBuilder('q')
            ->select('d')
            ->from('BackendBundle:Deal', 'd')
            ->andWhere('d.isActive = true');
    }

    /**
     * Return all model objects
     *
     * @return mixed
     */
    public function findAll()
    {
        return $this->em
            ->getRepository('BackendBundle:Deal')
            ->findAll();
    }

    /**
     * Return a new empty model object
     *
     * @return mixed
     */
    public function create()
    {
        return new Deal();
    }

    /**
     * Return the object model associated with {id}
     *
     * @param $id
     *
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function find($id)
    {
        $instance = $this->em
            ->getRepository('BackendBundle:Deal')
            ->find($id);

        if (!$instance) {
            throw new NotFoundHttpException(sprintf('The deal with id "%s" could not be found', $id));
        }

        return $instance;
    }

    /**
     * Deletes a model object referenced by {id}
     *
     * @param $id
     *
     * @return void
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws NotFoundHttpException
     */
    public function deleteById($id)
    {
        $instance = $this->find($id);

        $this->em->remove($instance);
        // flush the Doctrine's UnitOfWork
        $this->em->flush();
    }

    /**
     * Deletes an instance of a model object
     *
     * @param $instance
     *
     * @return mixed
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function delete($instance)
    {
        $this->em->remove($instance);
        $this->em->flush();
    }

    /**
     * Returns the top N (by creation date) deals
     *
     * @param int $n
     *
     * @return \Doctrine\ORM\Query
     */
    public function findTopDeals($n = 3)
    {
        return $this->em->getRepository('BackendBundle:Deal')->findRecentDeals($n);
    }

    /**
     * Finds a deal by the specified slug
     *
     * @param string $slug
     *
     * @return \BackendBundle\Entity\Deal
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function findBySlug($slug)
    {
        $object = $this->em->getRepository('BackendBundle:Deal')->findOneBy([
            'slug' => $slug,
        ]);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('Deal with slug "%s" could not be found', $slug));
        }

        return $object;
    }

    /**
     * Returns a list of Deals ordered by the desired parameters.
     *   SortingMode::SORT_NEW_DEALS
     *      Only deals created in the last 24h will be returned
     *   SortingMode::SORT_ENDING_SOON
     *      Only deals that will end in the next 24h will be returned
     *
     * @param int $mode
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findAllSorted($mode = SortingMode::SORT_NONE)
    {
        $allQuery = $this->findAllQuery();

        if (SortingMode::SORT_ENDING_SOON === $mode) {
            $allQuery = $allQuery
                ->andWhere('d.endsAt BETWEEN :now AND :other')
                ->setParameter('now', new \DateTime())
                ->setParameter('other', new \DateTime('+1 DAY'))
                ->addOrderBy('d.endsAt');
        } else if (SortingMode::SORT_NEW_DEALS === $mode) {
            $allQuery
                ->andWhere('d.createdAt BETWEEN :other AND :now')
                ->setParameter('other', new \DateTime('-1 DAY'))
                ->setParameter('now', new \DateTime())
                ->addOrderBy('d.endsAt');
        }

        return $allQuery;
    }

    /**
     * Returns the matching deals according to the filters/sorting
     * arguments
     *
     * @param array $filters
     * @param int   $sortingMode
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findMatchingDeals(array $filters, $sortingMode = SortingMode::SORT_NONE)
    {
        $query = $this->findAllSorted($sortingMode);

        if (array_key_exists('business', $filters) && isset($filters['business'])) {
            $query
                ->join('d.business', 'b')
                ->andWhere('b.id = :business')
                ->setParameter('business', $filters['business']->getId());
        }

        return $query;
    }

    /**
     * Returns all the deals that are up for disable action
     *
     * @param $strDate
     *
     * @return array
     */
    public function findDealsToDisable($strDate = 'NOW')
    {
        return $this->em->getRepository('BackendBundle:Deal')->findDealUntil($strDate);
    }

    /**
     * Save a given deal in the configured data storage
     *
     * @param \BackendBundle\Entity\Deal $deal
     *
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function save(Deal $deal)
    {
        $this->em->persist($deal);
    }

    /**
     * Disable the expired deals
     *
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function disableExpiredDeals()
    {
        $deals = $this->findDealsToDisable();

        foreach ($deals as $deal) {
            /** @var $deal Deal */
            $deal->disable();
            $this->save($deal);
        }

        $this->em->flush();
    }
}

