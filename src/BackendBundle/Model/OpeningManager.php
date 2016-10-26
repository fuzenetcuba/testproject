<?php

namespace BackendBundle\Model;

use BackendBundle\Entity\Candidate;
use BackendBundle\Entity\Opening;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
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
     * @return Query
     */
    public function findAllQuery()
    {
        return $this->em->createQueryBuilder()
            ->select('o')
            ->from('BackendBundle:Opening', 'o')
            // ->getQuery()
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
        return $this->em->getRepository('BackendBundle:Opening')
            ->findOneBy([
                'slug' => $slug
            ]);
    }

    public function fromRequest(Request $request)
    {
        $candidate = new Candidate();
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

        $slug = $request->request->get('slug');
        $opening = $this->findBySlug($slug);

        $candidate->opening = $opening;
        $candidate->firstName = $request->request->get('firstName');
        $candidate->lastName = $request->request->get('lastName');
        $candidate->middleName = $request->request->get('middleName');
        $candidate->address = $request->request->get('address');
        $candidate->city = $request->request->get('city');
        $candidate->state = $request->request->get('state');
        $candidate->zipCode = $request->request->get('zipCode');
        $candidate->socialNumber = $request->request->get('securityNumber');
        $candidate->adult = $request->request->get('adult');
        $candidate->phone = $request->request->get('phone');
        $candidate->availability = $request->request->get('availability');
        $candidate->availableHours = $request->request->get('availabilityHours');
        $candidate->weekAvailable = $request->request->get('weekHours');
        $candidate->startDate = new \DateTime($request->request->get('startDate'));
        $candidate->salary = $request->request->get('salary');
        $candidate->hasLicense = $request->request->get('hasDriverLicense');
        $candidate->licenseNumber = $request->request->get('licenseNumber');
        $candidate->licenseState = $request->request->get('licenseState');
        $candidate->licenseExpiration = $request->request->get('liceneExpiration');
        $candidate->legalWorker = $request->request->get('legal');
        $candidate->crime = $request->request->get('crime');
        $candidate->crimeExplain = $request->request->get('crimeExplain');
        $candidate->background = $request->request->get('background');
        $candidate->backExplain = $request->request->get('backExplain');
        $candidate->yearsHighschool = $request->request->get('highschoolYears');
        $candidate->hasDiploma = $request->request->get('diploma');
        $candidate->hasGED = $request->request->get('ged');
        $candidate->schools = $request->request->get('schools');
        $candidate->yearsCollege = $request->request->get('collegeYears');
        $candidate->collegeSchool = $request->request->get('collegeSchool');
        $candidate->collegeState = $request->request->get('collegeCity');
        $candidate->major = $request->request->get('major');
        $candidate->degree = $request->request->get('degree');
        $candidate->gpa = $request->request->get('gpa');
        $candidate->contactEmployers = $request->request->get('contactEmployer');
        $candidate->employmentName = $request->request->get('nameForEmployer');
        $candidate->employers = $request->request->get('employers');
        $candidate->skills = $request->request->get('skills');
        $candidate->bestFit = $request->request->get('bestFit');
        $candidate->references = $request->request->get('references');

        return $candidate;
    }

    public function saveCandidate(Candidate $candidate)
    {
        $this->em->persist($candidate);
        $this->em->flush();
    }

    public function findMatchingOpenings(array $data)
    {
        $query = $this->findAllQuery();
        $query->addOrderBy('o.position', 'ASC');

        return $query;
    }

    public function notifyManager(Candidate $candidate, $subject, $from, $content)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($this->careersEmail)
            ->setBody($content, 'text/html')
        ;

        $this->mailer->send($message);
    }
}