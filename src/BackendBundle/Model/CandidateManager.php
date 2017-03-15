<?php

namespace BackendBundle\Model;

use BackendBundle\Entity\Candidate;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Manager for the Feedback entity
 *
 * @package \BackendBundle\Model
 */
class CandidateManager implements ManagerInterface
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
            ->select('c')
            ->from('BackendBundle:Candidate', 'c');
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
            ->select('c')
            ->from('BackendBundle:Candidate', 'c')
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
        $candidate = new Candidate();

        return $candidate;
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
            ->getRepository('BackendBundle:Candidate')
            ->find($id);

        if (!$instance) {
            throw new NotFoundHttpException(sprintf('The candidate with id "%s" could not be found', $id));
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
     * Fetch cant of candidates of month
     *
     * @param  \DateTime $startMonth
     * @param  \DateTime $endMonth
     * @return array                            Amount of candidates
     */
    public function findCandidatesOfMonth(\DateTime $startMonth, \DateTime $endMonth)
    {
        $query = $this->em
            ->createQueryBuilder('q')
            ->select('count(c)')
            ->from('BackendBundle:Candidate', 'c')
            ->where('c.created BETWEEN :s AND :e')
            ->setParameter('s', $startMonth->format('Y-m-d'))
            ->setParameter('e', $endMonth->format('Y-m-d'));

        // multilanguage search criterias done introspecting the default locale
        $query = $query->getQuery()->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );

        return $query->getSingleScalarResult();
    }

    /**
     * Fetch list of amount of candidates per month
     *
     * @param  int $lasts                       Months to retrieve candidates
     * @return array                            List of candidates
     */
    public function findCandidatesOfLastMonths($lasts)
    {
        $list = array();

        $lasts -= 1;

        for ($i = $lasts; $i >= 1; $i--) {
            $start = new \DateTime('first day of ' . $i . ' months ago');
            $end = new \DateTime('last day of ' . $i . ' months ago');

            $list[0][$lasts - $i] = $start->format('F, Y');
            $list[1][$lasts - $i] = $this->findCandidatesOfMonth($start, $end);
        }

        $startCurrentMonth = new \DateTime('first day of this month');
        $endCurrentMonth = new \DateTime('last day of this month');

        $list[0][$lasts] = $startCurrentMonth->format('F, Y');
        $list[1][$lasts] = $this->findCandidatesOfMonth($startCurrentMonth, $endCurrentMonth);

        return $list;
    }

}