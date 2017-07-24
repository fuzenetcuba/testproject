<?php

namespace BackendBundle\Model;

use BackendBundle\Entity\Category;
use BackendBundle\Entity\SystemUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Manager for the Customer entity
 *
 * @package \BackendBundle\Model
 */
class CustomerManager implements ManagerInterface
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(EntityManager $entityManager, \Swift_Mailer $mailer)
    {
        $this->em = $entityManager;
        $this->mailer = $mailer;
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
            ->from('BackendBundle:SystemUser', 'f')
            ->where('f.roles LIKE :find')
            ->setParameter('find', '%ROLE_CUSTOMER%');
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
            ->from('BackendBundle:SystemUser', 'f')
            ->where('f.roles LIKE :find')
            ->setParameter('find', '%ROLE_CUSTOMER%')
            ->getQuery()
            ->getResult();
    }

    /**
     * Return all model objects
     *
     * @return mixed
     */
    public function findAllSubscribed()
    {
        return $this->em
            ->createQueryBuilder('q')
            ->select('f')
            ->from('BackendBundle:SystemUser', 'f')
            ->where('f.roles LIKE :find')
            ->andWhere('f.subscribed = true')
            ->setParameter('find', '%ROLE_CUSTOMER%')
            ->getQuery()
            ->getResult();
    }

    public function findAllSubscribedByEmail()
    {
        return $this->em->createQueryBuilder('q')
            ->select('s')
            ->from('BackendBundle:Subscription', 's')
            ->where('s.subscribed = true')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Return a new empty model object
     *
     * @return mixed
     */
    public function create()
    {
        $customer = new SystemUser();
        $customer->addRole('ROLE_CUSTOMER');

        return $customer;
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
            ->getRepository('BackendBundle:SystemUser')
            ->find($id);

        if (!$instance) {
            throw new NotFoundHttpException(sprintf('The customer with id "%s" could not be found', $id));
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
    public function findEmailCustomers()
    {
        return $this->em
            ->createQueryBuilder('q')
            ->select('f.email')
            ->from('BackendBundle:SystemUser', 'f')
            ->where('f.roles LIKE :find')
            ->setParameter('find', '%ROLE_CUSTOMER%')
            ->getQuery()
            ->getResult();
    }

    /**
     * Return all model objects
     *
     * @return mixed
     */
    public function findEmailRegistered()
    {
        return $this->em
            ->createQueryBuilder('q')
            ->select('f.email')
            ->from('BackendBundle:SystemUser', 'f')
            ->getQuery()
            ->getResult();
    }

    public function sendEmail(ArrayCollection $users, ArrayCollection $customEmails, $from, $subject, $content, $to = null, $bcc = false)
    {
        $emailUsers = $users->map(function ($user) {
            return $user->getEmail();
        });

        $emailUsers = new ArrayCollection(
            array_unique(array_merge($emailUsers->toArray(), $customEmails->toArray()))
        );

//        echo var_dump($emailUsers->toArray()); die;

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setBody($content, 'text/html')
        ;

        if($bcc){
            $message->setTo($to);
            $message->setBcc($emailUsers->toArray());
        } else {
            $message->setTo($emailUsers->toArray());
        }

        $this->mailer->send($message);
    }
}