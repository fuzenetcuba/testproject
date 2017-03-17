<?php

namespace BackendBundle\Model;

use BackendBundle\Entity\Alert;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Manager for the Alert entity
 *
 * @package \BackendBundle\Model
 */
class AlertManager implements ManagerInterface
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
            ->select('a')
            ->from('BackendBundle:Alert', 'a');
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
            ->select('a')
            ->from('BackendBundle:Alert', 'a')
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
        $alert = new Alert();

        return $alert;
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
            ->getRepository('BackendBundle:Alert')
            ->find($id);

        if (!$instance) {
            throw new NotFoundHttpException(sprintf('The alert with id "%s" could not be found', $id));
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
     * Returns the alerts that are checked
     *
     * @return array
     */
    public function findAlertsChecked()
    {
        $query = $this->findAllQuery();

        return $query
            ->where('a.checked = true')
            ->getQuery()
            ->getResult();
    }

    /**
     * Returns the alerts that are not checked
     *
     * @return array
     */
    public function findAlertsUnchecked()
    {
        $query = $this->findAllQuery();

        return $query
            ->where('a.checked = false')
            ->getQuery()
            ->getResult();
    }

    /**
     * Remove all alert older than # months
     *
     * @param $months
     *
     * @return mixed
     */
    public function deleteAlertsOlderThan($months)
    {
        $monthsDate = new \DateTime($months . ' months ago');

        return $this->em
            ->createQueryBuilder('q')
            ->delete('BackendBundle:Alert', 'a')
            ->where('a.date <= :date')
            ->andWhere('a.checked = true')
            ->setParameter('date', $monthsDate->format('Y-m-d'))
            ->getQuery()
            ->getResult();
    }

}