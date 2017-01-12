<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Class Candidate
 *
 * @package \BackendBundle\Entity
 * @ORM\Entity()
 * @DoctrineAssert\UniqueEntity(
 *     fields={"socialNumber", "opening"},
 *     errorPath="socialNumber",
 *     message="Already exist an application for this Position with this Social Security or Tax Id Number",
 *     groups={"application"}
 * )
 */
class Candidate
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $middleName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $state;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $socialNumber;

    /**
     * @ORM\Column(type="boolean")
     */
    private $adult;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $availableHours;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $weekAvailable;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $startDate;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $salary;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $availability;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $hasLicense;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $licenseNumber;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $licenseState;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $licenseExpiration;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $legalWorker;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $crime;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $crimeExplain;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $background;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $backExplain;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $yearsHighschool;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $hasDiploma;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $hasGED;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $schools;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $yearsCollege;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $collegeSchool;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $collegeState;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $major;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $degree;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $gpa;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $contactEmployers;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $employmentName;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $employers;

    /**
     * @ORM\Column(name="refs", type="json_array", nullable=true)
     */
    private $references;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $skills;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $bestFit;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\File(mimeTypes={ "application/pdf" })
     */
    private $cv;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\File(mimeTypes={ "application/pdf" })
     */
    private $coverLetter;

    /**
     * @var Opening
     *
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\Opening")
     */
    private $opening;

    /**
     * @return mixed
     */
    public function getCv()
    {
        return $this->cv;
    }

    /**
     * @param mixed $cv
     *
     * @return $this
     */
    public function setCv($cv)
    {
        $this->cv = $cv;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCoverLetter()
    {
        return $this->coverLetter;
    }

    /**
     * @param mixed $coverLetter
     *
     * @return $this
     */
    public function setCoverLetter($coverLetter)
    {
        $this->coverLetter = $coverLetter;

        return $this;
    }

    public function fullName()
    {
        return sprintf('%s %s %s', $this->firstName, $this->middleName, $this->lastName);
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return Candidate
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return Candidate
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set middleName
     *
     * @param string $middleName
     * @return Candidate
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;

        return $this;
    }

    /**
     * Get middleName
     *
     * @return string 
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Candidate
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Candidate
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return Candidate
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set zipCode
     *
     * @param string $zipCode
     * @return Candidate
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * Get zipCode
     *
     * @return string 
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Set socialNumber
     *
     * @param string $socialNumber
     * @return Candidate
     */
    public function setSocialNumber($socialNumber)
    {
        $this->socialNumber = $socialNumber;

        return $this;
    }

    /**
     * Get socialNumber
     *
     * @return string 
     */
    public function getSocialNumber()
    {
        return $this->socialNumber;
    }

    /**
     * Set adult
     *
     * @param boolean $adult
     * @return Candidate
     */
    public function setAdult($adult)
    {
        $this->adult = $adult;

        return $this;
    }

    /**
     * Get adult
     *
     * @return boolean 
     */
    public function getAdult()
    {
        return $this->adult;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Candidate
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set availableHours
     *
     * @param string $availableHours
     * @return Candidate
     */
    public function setAvailableHours($availableHours)
    {
        $this->availableHours = $availableHours;

        return $this;
    }

    /**
     * Get availableHours
     *
     * @return string 
     */
    public function getAvailableHours()
    {
        return $this->availableHours;
    }

    /**
     * Set weekAvailable
     *
     * @param array $weekAvailable
     * @return Candidate
     */
    public function setWeekAvailable($weekAvailable)
    {
        $this->weekAvailable = $weekAvailable;

        return $this;
    }

    /**
     * Get weekAvailable
     *
     * @return array 
     */
    public function getWeekAvailable()
    {
        return $this->weekAvailable;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Candidate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set salary
     *
     * @param string $salary
     * @return Candidate
     */
    public function setSalary($salary)
    {
        $this->salary = $salary;

        return $this;
    }

    /**
     * Get salary
     *
     * @return string 
     */
    public function getSalary()
    {
        return $this->salary;
    }

    /**
     * Set availability
     *
     * @param string $availability
     * @return Candidate
     */
    public function setAvailability($availability)
    {
        $this->availability = $availability;

        return $this;
    }

    /**
     * Get availability
     *
     * @return string 
     */
    public function getAvailability()
    {
        return $this->availability;
    }

    /**
     * Set hasLicense
     *
     * @param string $hasLicense
     * @return Candidate
     */
    public function setHasLicense($hasLicense)
    {
        $this->hasLicense = $hasLicense;

        return $this;
    }

    /**
     * Get hasLicense
     *
     * @return string 
     */
    public function getHasLicense()
    {
        return $this->hasLicense;
    }

    /**
     * Set licenseNumber
     *
     * @param string $licenseNumber
     * @return Candidate
     */
    public function setLicenseNumber($licenseNumber)
    {
        $this->licenseNumber = $licenseNumber;

        return $this;
    }

    /**
     * Get licenseNumber
     *
     * @return string 
     */
    public function getLicenseNumber()
    {
        return $this->licenseNumber;
    }

    /**
     * Set licenseState
     *
     * @param string $licenseState
     * @return Candidate
     */
    public function setLicenseState($licenseState)
    {
        $this->licenseState = $licenseState;

        return $this;
    }

    /**
     * Get licenseState
     *
     * @return string 
     */
    public function getLicenseState()
    {
        return $this->licenseState;
    }

    /**
     * Set licenseExpiration
     *
     * @param string $licenseExpiration
     * @return Candidate
     */
    public function setLicenseExpiration($licenseExpiration)
    {
        $this->licenseExpiration = $licenseExpiration;

        return $this;
    }

    /**
     * Get licenseExpiration
     *
     * @return string 
     */
    public function getLicenseExpiration()
    {
        return $this->licenseExpiration;
    }

    /**
     * Set legalWorker
     *
     * @param string $legalWorker
     * @return Candidate
     */
    public function setLegalWorker($legalWorker)
    {
        $this->legalWorker = $legalWorker;

        return $this;
    }

    /**
     * Get legalWorker
     *
     * @return string 
     */
    public function getLegalWorker()
    {
        return $this->legalWorker;
    }

    /**
     * Set crime
     *
     * @param string $crime
     * @return Candidate
     */
    public function setCrime($crime)
    {
        $this->crime = $crime;

        return $this;
    }

    /**
     * Get crime
     *
     * @return string 
     */
    public function getCrime()
    {
        return $this->crime;
    }

    /**
     * Set crimeExplain
     *
     * @param string $crimeExplain
     * @return Candidate
     */
    public function setCrimeExplain($crimeExplain)
    {
        $this->crimeExplain = $crimeExplain;

        return $this;
    }

    /**
     * Get crimeExplain
     *
     * @return string 
     */
    public function getCrimeExplain()
    {
        return $this->crimeExplain;
    }

    /**
     * Set background
     *
     * @param string $background
     * @return Candidate
     */
    public function setBackground($background)
    {
        $this->background = $background;

        return $this;
    }

    /**
     * Get background
     *
     * @return string 
     */
    public function getBackground()
    {
        return $this->background;
    }

    /**
     * Set backExplain
     *
     * @param string $backExplain
     * @return Candidate
     */
    public function setBackExplain($backExplain)
    {
        $this->backExplain = $backExplain;

        return $this;
    }

    /**
     * Get backExplain
     *
     * @return string 
     */
    public function getBackExplain()
    {
        return $this->backExplain;
    }

    /**
     * Set yearsHighschool
     *
     * @param string $yearsHighschool
     * @return Candidate
     */
    public function setYearsHighschool($yearsHighschool)
    {
        $this->yearsHighschool = $yearsHighschool;

        return $this;
    }

    /**
     * Get yearsHighschool
     *
     * @return string 
     */
    public function getYearsHighschool()
    {
        return $this->yearsHighschool;
    }

    /**
     * Set hasDiploma
     *
     * @param string $hasDiploma
     * @return Candidate
     */
    public function setHasDiploma($hasDiploma)
    {
        $this->hasDiploma = $hasDiploma;

        return $this;
    }

    /**
     * Get hasDiploma
     *
     * @return string 
     */
    public function getHasDiploma()
    {
        return $this->hasDiploma;
    }

    /**
     * Set hasGED
     *
     * @param string $hasGED
     * @return Candidate
     */
    public function setHasGED($hasGED)
    {
        $this->hasGED = $hasGED;

        return $this;
    }

    /**
     * Get hasGED
     *
     * @return string 
     */
    public function getHasGED()
    {
        return $this->hasGED;
    }

    /**
     * Set schools
     *
     * @param array $schools
     * @return Candidate
     */
    public function setSchools($schools)
    {
        $this->schools = $schools;

        return $this;
    }

    /**
     * Get schools
     *
     * @return array 
     */
    public function getSchools()
    {
        return $this->schools;
    }

    /**
     * Set yearsCollege
     *
     * @param string $yearsCollege
     * @return Candidate
     */
    public function setYearsCollege($yearsCollege)
    {
        $this->yearsCollege = $yearsCollege;

        return $this;
    }

    /**
     * Get yearsCollege
     *
     * @return string 
     */
    public function getYearsCollege()
    {
        return $this->yearsCollege;
    }

    /**
     * Set collegeSchool
     *
     * @param string $collegeSchool
     * @return Candidate
     */
    public function setCollegeSchool($collegeSchool)
    {
        $this->collegeSchool = $collegeSchool;

        return $this;
    }

    /**
     * Get collegeSchool
     *
     * @return string 
     */
    public function getCollegeSchool()
    {
        return $this->collegeSchool;
    }

    /**
     * Set collegeState
     *
     * @param string $collegeState
     * @return Candidate
     */
    public function setCollegeState($collegeState)
    {
        $this->collegeState = $collegeState;

        return $this;
    }

    /**
     * Get collegeState
     *
     * @return string 
     */
    public function getCollegeState()
    {
        return $this->collegeState;
    }

    /**
     * Set major
     *
     * @param string $major
     * @return Candidate
     */
    public function setMajor($major)
    {
        $this->major = $major;

        return $this;
    }

    /**
     * Get major
     *
     * @return string 
     */
    public function getMajor()
    {
        return $this->major;
    }

    /**
     * Set degree
     *
     * @param string $degree
     * @return Candidate
     */
    public function setDegree($degree)
    {
        $this->degree = $degree;

        return $this;
    }

    /**
     * Get degree
     *
     * @return string 
     */
    public function getDegree()
    {
        return $this->degree;
    }

    /**
     * Set gpa
     *
     * @param string $gpa
     * @return Candidate
     */
    public function setGpa($gpa)
    {
        $this->gpa = $gpa;

        return $this;
    }

    /**
     * Get gpa
     *
     * @return string 
     */
    public function getGpa()
    {
        return $this->gpa;
    }

    /**
     * Set contactEmployers
     *
     * @param string $contactEmployers
     * @return Candidate
     */
    public function setContactEmployers($contactEmployers)
    {
        $this->contactEmployers = $contactEmployers;

        return $this;
    }

    /**
     * Get contactEmployers
     *
     * @return string 
     */
    public function getContactEmployers()
    {
        return $this->contactEmployers;
    }

    /**
     * Set employmentName
     *
     * @param string $employmentName
     * @return Candidate
     */
    public function setEmploymentName($employmentName)
    {
        $this->employmentName = $employmentName;

        return $this;
    }

    /**
     * Get employmentName
     *
     * @return string 
     */
    public function getEmploymentName()
    {
        return $this->employmentName;
    }

    /**
     * Set employers
     *
     * @param array $employers
     * @return Candidate
     */
    public function setEmployers($employers)
    {
        $this->employers = $employers;

        return $this;
    }

    /**
     * Get employers
     *
     * @return array 
     */
    public function getEmployers()
    {
        return $this->employers;
    }

    /**
     * Set references
     *
     * @param array $references
     * @return Candidate
     */
    public function setReferences($references)
    {
        $this->references = $references;

        return $this;
    }

    /**
     * Get references
     *
     * @return array 
     */
    public function getReferences()
    {
        return $this->references;
    }

    /**
     * Set skills
     *
     * @param string $skills
     * @return Candidate
     */
    public function setSkills($skills)
    {
        $this->skills = $skills;

        return $this;
    }

    /**
     * Get skills
     *
     * @return string 
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * Set bestFit
     *
     * @param string $bestFit
     * @return Candidate
     */
    public function setBestFit($bestFit)
    {
        $this->bestFit = $bestFit;

        return $this;
    }

    /**
     * Get bestFit
     *
     * @return string 
     */
    public function getBestFit()
    {
        return $this->bestFit;
    }

    /**
     * Set opening
     *
     * @param \BackendBundle\Entity\Opening $opening
     * @return Candidate
     */
    public function setOpening(\BackendBundle\Entity\Opening $opening = null)
    {
        $this->opening = $opening;

        return $this;
    }

    /**
     * Get opening
     *
     * @return \BackendBundle\Entity\Opening 
     */
    public function getOpening()
    {
        return $this->opening;
    }
}
