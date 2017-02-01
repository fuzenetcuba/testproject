<?php

namespace BackendBundle\Model;

use BackendBundle\Entity\Business;
use BackendBundle\Entity\OpeningCategory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Manager for the OpeningCategory entity
 *
 * @package \BackendBundle\Model
 */
class OpeningCategoryManager implements ManagerInterface
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
            ->from('BackendBundle:OpeningCategory', 'f')
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
            ->getRepository('BackendBundle:OpeningCategory')
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
        return new OpeningCategory();
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
            ->getRepository('BackendBundle:OpeningCategory')
            ->find($id)
        ;

        if (!$instance) {
            throw new NotFoundHttpException(sprintf('The category with id "%s" could not be found', $id));
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
     * Fetchs a list of openings that have some position available
     *
     * @param  Business $business Opening
     * @return array             List of businesses
     */
    public function findOpeningsCategoryWithBusiness(Business $business)
    {
        $query = $this->findAllQuery();

        $query
            ->join('f.openings', 'o')
            ->join('o.business', 'b')
            ->andWhere('b.id = :id')
            ->setParameter('id', $business->getId());

        // multilanguage search criterias done introspecting the default locale
        $query = $query->getQuery()->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );

        return $query->getResult();
    }
}