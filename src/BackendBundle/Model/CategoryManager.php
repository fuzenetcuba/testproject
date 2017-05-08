<?php

namespace BackendBundle\Model;

use BackendBundle\Entity\Category;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Manager for the Category entity
 *
 * @package \BackendBundle\Model
 */
class CategoryManager implements ManagerInterface
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
            ->from('BackendBundle:Category')
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
            ->getRepository('BackendBundle:Category')
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
        return new Category();
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
            ->getRepository('BackendBundle:Category')
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
     * Find a category by the slug
     *
     * @param $slug
     *
     * @return \BackendBundle\Entity\Category
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function findBySlug($slug)
    {
        $object = $this->em
            ->createQuery('SELECT d FROM BackendBundle:Category d WHERE d.slug = :slug')
            ->setParameter('slug', $slug)
            ->setHint(
                Query::HINT_CUSTOM_OUTPUT_WALKER,
                'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
            )
            ->getOneOrNullResult()
        ;

        if (!$object) {
            $object = $this->em
                ->createQuery('SELECT d FROM BackendBundle:Category d WHERE d.slug = :slug')
                ->setParameter('slug', $slug)
                ->getOneOrNullResult()
            ;

            if (!$object) {

                $qb = $this->em
                ->getRepository('Gedmo\Translatable\Entity\Translation')
                ->createQueryBuilder('t');

                $qb->orWhere('t.field = :f AND t.content = :c');
                $qb->setParameter('f', 'slug');
                $qb->setParameter('c', $slug);

                /** @var \Gedmo\Translatable\Entity\Translation[] $trs */
                $trs = $qb->getQuery()->getResult();

                if(!$trs){
                    throw new NotFoundHttpException(sprintf('Category with slug "%s" could not be found', $slug));
                    
                } else {
                    $object = $this->find($trs[0]->getForeignKey());
                    
                    if (!$object) {
                        throw new NotFoundHttpException(sprintf('Category with slug "%s" could not be found', $slug));
                    }
                }
            }
        }

        return $object;
    }
}