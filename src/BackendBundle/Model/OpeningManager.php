<?php

namespace BackendBundle\Model;

use BackendBundle\Entity\Business;
use BackendBundle\Entity\Candidate;
use BackendBundle\Entity\Opening;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Spraed\PDFGeneratorBundle\PDFGenerator\PDFGenerator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
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
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var PDFGenerator
     */
    private $pdfGenerator;

    /**
     * @var string
     */
    private $storePath;

    /**
     * @var string
     */
    private $reportsPath;

    /**
     * @var string
     */
    private $careersEmail;


    public function __construct(EntityManager $entityManager, \Swift_Mailer $mailer, Filesystem $filesystem, PDFGenerator $PDFGenerator, $path, $reportsPath, $email)
    {
        $this->em = $entityManager;
        $this->mailer = $mailer;
        $this->filesystem = $filesystem;
        $this->pdfGenerator = $PDFGenerator;
        $this->storePath = $path;
        $this->reportsPath = $reportsPath;
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
        $candidate->setStartDate(\DateTime::createFromFormat('d/m/Y', $request->request->get('startDate')));
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
            $query
                ->join('o.business', 'b')
                ->andWhere('b.slug = :slug')
                ->setParameter('slug', $conditions['business']);
        }

        if (isset($conditions['categories'])) {
            $query
                ->join('o.categories', 'c')
                ->andWhere('c.id = :id')
                ->setParameter('id', $conditions['categories']);
        }

        $query->addOrderBy('o.position', 'ASC');

        return $query;
    }

    public function notifyManager(Candidate $candidate, $subject, $from, $content, $fileContent = null)
    {
        $pdfFile = $this->createCandidatePDFReport($candidate, $fileContent);

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($this->careersEmail)
            ->setBody($content, 'text/html')
            ->attach(\Swift_Attachment::fromPath($pdfFile));

        if ($candidate->getCv() != "tmp-cv") {
            $message->attach(\Swift_Attachment::fromPath($this->storePath . DIRECTORY_SEPARATOR . $candidate->getCv()));
        }
        if ($candidate->getCoverLetter() != "tmp-cover") {
            $message->attach(\Swift_Attachment::fromPath($this->storePath . DIRECTORY_SEPARATOR . $candidate->getCoverLetter()));
        }

        $this->mailer->send($message);

        // deleting temp files
        $this->deleteCandidatePDFReport($candidate);
    }

    public function notifyManagerWithoutSSL(Candidate $candidate, $subject, $from, $content, $host, $port, $encrytion, $authMode, $user, $pass)
    {
        //Estas lineas son para cuando el servidor tiene un certificado no valido
        $https['ssl']['verify_peer'] = FALSE;
        $https['ssl']['verify_peer_name'] = FALSE;

        $transport = \Swift_SmtpTransport::newInstance(
            $host,
            $port,
            $encrytion)
            ->setAuthMode($authMode)
            ->setUsername($user)
            ->setPassword($pass)
            ->setStreamOptions($https);

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo('yulioaj@uci.cu'/*$this->careersEmail*/)
            ->setBody($content, 'text/html')
            ->attach(\Swift_Attachment::fromPath(realpath($this->reportsPath) . DIRECTORY_SEPARATOR . 'google-operators.pdf'));

        $this->mailer->newInstance($transport)->send($message);
    }

    /**
     * Create a PDF file for Candidate entity
     *
     * @param Candidate $candidate
     * @param $fileContent
     *
     * @return string The absolute path to the PDF file
     *
     * $fileContent must be defined before calling this method like this:
     * $fileContent = $this->renderView('candidate/pdf.html.twig', array(
     *      'entity' => $entity,    // $entity is the Candidate entity
     * ));
     */
    private function createCandidatePDFReport(Candidate $candidate, $fileContent)
    {
        try {
            $absolutePath = realpath($this->reportsPath);
            $pdfFile = $absolutePath . DIRECTORY_SEPARATOR . trim($candidate->getSocialNumber() . "_" . $candidate->getOpening()->getId()) . ".pdf";

            $this->filesystem->dumpFile($pdfFile, $this->pdfGenerator->generatePDF($fileContent));

            return $pdfFile;

        } catch (IOExceptionInterface $e) {
            echo "An error occurred while creating files at " . $e->getPath();
        }
    }

    /**
     * Delete a PDF file of a Candidate entity
     * @param Candidate $candidate
     */
    private function deleteCandidatePDFReport(Candidate $candidate)
    {
        try {
            $absolutePath = realpath($this->reportsPath);
            $pdfFile = $absolutePath . DIRECTORY_SEPARATOR . trim($candidate->getSocialNumber() . "_" . $candidate->getOpening()->getId()) . ".pdf";
            if ($this->filesystem->exists($pdfFile)) {
                $this->filesystem->remove($pdfFile);
            }
        } catch (IOExceptionInterface $e) {
            echo "An error occurred while creating files at " . $e->getPath();
        }
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
            ->setParameter('id', $business->getId());

        // multilanguage search criterias done introspecting the default locale
        $query = $query->getQuery()->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );

        return $query->getResult();
    }
}