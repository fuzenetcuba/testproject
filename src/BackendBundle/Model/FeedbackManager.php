<?php

namespace BackendBundle\Model;

use BackendBundle\Entity\Feedback;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Manager for the Feedback entity
 *
 * @package \BackendBundle\Model
 */
class FeedbackManager implements ManagerInterface
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
            ->select('f')
            ->from('BackendBundle:Feedback', 'f');
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
            ->select('f')
            ->from('BackendBundle:Feedback', 'f')
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
        $feedback = new Feedback();

        return $feedback;
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
            ->getRepository('BackendBundle:Feedback')
            ->find($id);

        if (!$instance) {
            throw new NotFoundHttpException(sprintf('The feedback with id "%s" could not be found', $id));
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

}