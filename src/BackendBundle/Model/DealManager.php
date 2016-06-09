<?php

namespace BackendBundle\Model;

use BackendBundle\Entity\Deal;
use Doctrine\ORM\EntityManager;
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
     * @return mixed
     */
    public function findAllQuery()
    {
        return $this->em
            ->createQueryBuilder('q')
            ->select('d')
            ->from('BackendBundle:Deal', 'd')
        ;
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
            ->findAll()
            ;
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
            ->find($id)
        ;

        if (!$instance) {
            throw new NotFoundHttpException(sprintf('The deal with id "%s" could not be found', $id));
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
     * Returns the top N (by creation date) deals
     *
     * @param int $n
     *
     * @return \Doctrine\ORM\Query
     */
    public function findTopDeals($n = 3)
    {
        return $this->em->getRepository('BackendBundle:Deal')->findTopDeals($n);
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
            'slug' => $slug
        ]);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('Deal with slug "%s" could not be found', $slug));
        }

        return $object;
    }
}