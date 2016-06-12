<?php

namespace BackendBundle\Model;

use BackendBundle\Entity\Business;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Manager for the Business entity
 *
 * @package \BackendBundle\Model
 */
class BusinessManager implements ManagerInterface
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
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findAllQuery()
    {
        return $this->em
            ->createQueryBuilder('q')
            ->select('b')
            ->from('BackendBundle:Business', 'b')
            ;
    }

    /**
     * Return all model objects
     *
     * @return \BackendBundle\Entity\Business[]
     */
    public function findAll()
    {
        return $this->em
            ->getRepository('BackendBundle:Business')
            ->findAll()
            ;
    }

    /**
     * Return a new empty model object
     *
     * @return \BackendBundle\Entity\Business
     */
    public function create()
    {
        return new Business();
    }

    /**
     * Return the object model associated with {id}
     *
     * @param $id
     *
     * @return Business
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function find($id)
    {
        $instance = $this->em
            ->getRepository('BackendBundle:Business')
            ->find($id)
        ;

        if (!$instance) {
            throw new NotFoundHttpException(sprintf('The business with id "%s" could not be found', $id));
        }
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
     * @return void
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function delete($instance)
    {
        $this->em->remove($instance);
        $this->em->flush();
    }

    /**
     * Find a bussiness by the slug
     *
     * @param $slug
     *
     * @return \BackendBundle\Entity\Business
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function findBySlug($slug)
    {
        $object = $this->em->getRepository('BackendBundle:Business')
            ->findOneBy(['slug' => $slug])
        ;

        if (!$object) {
            throw new NotFoundHttpException(sprintf('Business with slug "%s" could not be found', $slug));
        }

        return $object;
    }

    /**
     * Returns a list of related businesses: a random selection of business in
     * the same categories as the original
     *
     * @param \BackendBundle\Entity\Business $business
     * @param int                            $max
     *
     * @return array|Business[]
     */
    public function findRelatedBusinesses(Business $business, $max = 4)
    {
        return $this->em->getRepository('BackendBundle:Business')
            ->findRelatedBusinesses($business, $max)
            ;
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

        if (SortingMode::SORT_ALPHABETICALLY === $mode) {
            $allQuery = $allQuery
                ->addOrderBy('b.name');
        } else if (SortingMode::SORT_CATEGORY === $mode) {
            $allQuery
                ->join('b.categories', 'c')
                ->addOrderBy('c.name')
            ;
        } else if (SortingMode::SORT_RECENT_DEALS === $mode) {
            $allQuery
                ->join('b.deals', 'd')
                ->addOrderBy('d.endsAt')
            ;
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

        if (array_key_exists('category', $filters) && isset($filters['category'])) {
            $query
                ->join('b.categories', 'c')
                ->andWhere('c.id = :category')
                ->orWhere('c.parent = :category')
                ->setParameter('category', $filters['category']->getId())
            ;
        }

        if (array_key_exists('search', $filters) && isset($filters['search'])) {
            $query
                ->andWhere('b.name LIKE :search OR b.description LIKE :search')
                ->setParameter('search', sprintf('%%%s%%', $filters['search']))
            ;
        }

        return $query;
    }
}