<?php

namespace BackendBundle\Model;

use BackendBundle\Entity\Business;
use BackendBundle\Entity\Candidate;
use BackendBundle\Entity\Opening;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class OpeningManager
 *
 * @package \BackendBundle\Model
 */
class OpeningManager implements ManagerInterface
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var string
     */
    private $storePath;

    /**
     * @var string
     */
    private $careersEmail;


    public function __construct(EntityManager $entityManager, \Swift_Mailer $mailer, $path, $email)
    {
        $this->em = $entityManager;
        $this->mailer = $mailer;
        $this->storePath = $path;
        $this->careersEmail = $email;
    }

    /**
     * Return the DQL to fetch all model objects
     *
     * @return QueryBuilder
     */
    public function findAllQuery()
    {
        return $this->em->createQueryBuilder()
            ->select('o')
            ->from('BackendBundle:Opening', 'o')// ->getQuery()
            ;
    }

    /**
     * Return all model objects
     *
     * @return mixed
     */
    public function findAll()
    {
        return $this->findAllQuery()->getResult();
    }

    /**
     * Return a new empty model object
     *
     * @return mixed
     */
    public function create()
    {
        return new Opening();
    }

    /**
     * Return the object model associated with {id}
     *
     * @param $id
     *
     * @return Opening
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function find($id)
    {
        $instance = $this->em
            ->getRepository('BackendBundle:Opening')
            ->find($id);

        if (!$instance) {
            throw new NotFoundHttpException(sprintf('The opening with id "%s" could not be found', $id));
        }

        return $instance;
    }

    /**
     * Deletes a model object referenced by {id}
     *
     * @param $id
     *
     * @return void
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
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

    public function findBySlug($slug)
    {
        $object = $this->em
            ->createQuery('SELECT d FROM BackendBundle:Opening d WHERE d.slug = :slug')
            ->setParameter('slug', $slug)
            ->setHint(
                Query::HINT_CUSTOM_OUTPUT_WALKER,
                'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
            )
            ->getOneOrNullResult();

        if (!$object) {

            $object = $this->em
                ->createQuery('SELECT d FROM BackendBundle:Opening d WHERE d.slug = :slug')
                ->setParameter('slug', $slug)
                ->getOneOrNullResult();

            if (!$object) {
                throw new NotFoundHttpException(sprintf('Opening with slug "%s" could not be found', $slug));
            }
        }

        return $object;
    }

    public function fromRequest(Request $request)
    {
        $candidate = new Candidate();

        // Assign tmp names for Cover letter and CV
        $candidate->setCv("tmp-cv");
        $candidate->setCoverLetter("tmp-cover");

        $slug = $request->request->get('slug');
        $opening = $this->findBySlug($slug);

        $candidate->setOpening($opening);
        $candidate->setFirstName($request->request->get('firstName'));
        $candidate->setLastName($request->request->get('lastName'));
        $candidate->setMiddleName($request->request->get('middleName'));
        $candidate->setAddress($request->request->get('address'));
        $candidate->setCity($request->request->get('city'));
        $candidate->setState($request->request->get('state'));
        $candidate->setZipCode($request->request->get('zipCode'));
        $candidate->setSocialNumber($request->request->get('securityNumber'));
        $candidate->setAdult($request->request->get('adult'));
        $candidate->setPhone($request->request->get('phone'));
        $candidate->setAvailability($request->request->get('availability'));
        $candidate->setAvailableHours($request->request->get('availabilityHours'));
        $candidate->setWeekAvailable($request->request->get('weekHours'));
        $candidate->setStartDate(new \DateTime($request->request->get('startDate')));
        $candidate->setSalary($request->request->get('salary'));
        $candidate->setHasLicense($request->request->get('hasDriverLicense'));
        $candidate->setLicenseNumber($request->request->get('licenseNumber'));
        $candidate->setLicenseState($request->request->get('licenseState'));
        $candidate->setLicenseExpiration($request->request->get('liceneExpiration'));
        $candidate->setLegalWorker($request->request->get('legal'));
        $candidate->setCrime($request->request->get('crime'));
        $candidate->setCrimeExplain($request->request->get('crimeExplain'));
        $candidate->setBackground($request->request->get('background'));
        $candidate->setBackExplain($request->request->get('backExplain'));
        $candidate->setYearsHighschool($request->request->get('highschoolYears'));
        $candidate->setHasDiploma($request->request->get('diploma'));
        $candidate->setHasGED($request->request->get('ged'));
        $candidate->setSchools($request->request->get('schools'));
        $candidate->setYearsCollege($request->request->get('collegeYears'));
        $candidate->setCollegeSchool($request->request->get('collegeSchool'));
        $candidate->setCollegeState($request->request->get('collegeCity'));
        $candidate->setMajor($request->request->get('major'));
        $candidate->setDegree($request->request->get('degree'));
        $candidate->setGpa($request->request->get('gpa'));
        $candidate->setContactEmployers($request->request->get('contactEmployer'));
        $candidate->setEmploymentName($request->request->get('nameForEmployer'));
        $candidate->setEmployers($request->request->get('employers'));
        $candidate->setSkills($request->request->get('skills'));
        $candidate->setBestFit($request->request->get('bestFit'));
        $candidate->setReferences($request->request->get('references'));

        return $candidate;
    }

    public function storeFilesFromRequest(Request $request, Candidate $candidate)
    {

        /** @var UploadedFile $cv */
        $cv = $request->files->get('cv');

        /** @var UploadedFile $coverLetter */
        $coverLetter = $request->files->get('cover');

        if (null !== $cv) {
            $cvName = uniqid('cv-', true) . '.' . $cv->guessExtension();

            $cv->move(
                $this->storePath,
                $cvName
            );

            $candidate->setCv($cvName);
        }

        if (null !== $coverLetter) {
            $coverName = uniqid('cover-', true) . '.' . $coverLetter->guessExtension();

            $coverLetter->move(
                $this->storePath,
                $coverName
            );

            $candidate->setCoverLetter($coverName);
        }
    }

    public function saveCandidate(Candidate $candidate)
    {
        $this->em->persist($candidate);
        $this->em->flush();
    }

    public function findMatchingOpenings(array $conditions = [])
    {
        $query = $this->findAllQuery();

        if (isset($conditions['business'])) {
            $query->join('o.business', 'b')
                ->andWhere('b.slug = :slug')
                ->setParameter('slug', $conditions['business']);
        }

        if (isset($conditions['opening'])) {
            $query->andWhere('o.id = :id')
                ->setParameter('id', $conditions['opening']);
        }

        $query->addOrderBy('o.position', 'ASC');

        return $query;
    }

    public function notifyManager(Candidate $candidate, $subject, $from, $content)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($this->careersEmail)
            ->setBody($content, 'text/html');

        $this->mailer->send($message);
    }

    /**
     * Fetchs a list of openings that have some position available
     * 
     * @param  Opening $business Opening
     * @return array             List of businesses
     */
    public function findOpeningsWithBusiness(Business $business)
    {
        $query = $this->findAllQuery();

        $query
            ->join('o.business', 'b')
            ->andWhere('b.id = :id')
            ->setParameter('id', $business->getId())
        ;

        // multilanguage search criterias done introspecting the default locale
        $query = $query->getQuery()->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );

        return $query->getResult();
    }
}