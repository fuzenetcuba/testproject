<?php

namespace BackendBundle\Model;

use BackendBundle\Entity\Category;
use BackendBundle\Entity\Subscription;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Manager for the Subscription entity
 *
 * @package \BackendBundle\Model
 */
class SubscriptionManager implements ManagerInterface
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
     * @return mixed
     */
    public function findAllQuery()
    {
        return $this->em
            ->createQueryBuilder('q')
            ->select('s')
            ->from('BackendBundle:Subscription', 's');
    }

    /**
     * Return all model objects
     *
     * @return mixed
     */
    public function findAll()
    {
        return $this->em
            ->createQueryBuilder('q')
            ->select('s')
            ->from('BackendBundle:Subscription', 's')
            ->getQuery()
            ->getResult();
    }

    /**
     * Return a new empty model object
     *
     * @return mixed
     */
    public function create()
    {
        $subscription = new Subscription();

        return $subscription;
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
            ->getRepository('BackendBundle:Subscription')
            ->find($id);

        if (!$instance) {
            throw new NotFoundHttpException(sprintf('The subscription with id "%s" could not be found', $id));
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
     * Return all model objects
     *
     * @return mixed
     */
    public function findEmailSubscriptions()
    {
        return $this->em
            ->createQueryBuilder('q')
            ->select('s.email')
            ->from('BackendBundle:Subscription', 's')
            ->getQuery()
            ->getResult();
    }

    /**
     * Returns the users that are subscribed to a given category
     *
     * @param \BackendBundle\Entity\Category $category
     *
     * @return array
     */
    public function findSubscribedsInCategory(Category $category)
    {
        return $this->em->createQueryBuilder('u')
            ->select('s.email')
            ->from('BackendBundle:Subscription', 's')
            ->leftJoin('s.categories', 'c')
            ->where('c.id = :id')
            ->setParameter('id', $category->getId())
            ->getQuery()
            ->getResult();
    }

}