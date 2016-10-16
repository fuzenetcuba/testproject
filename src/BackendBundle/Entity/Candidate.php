<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Candidate
 *
 * @package \BackendBundle\Entity
 * @ORM\Entity
 */
class Candidate
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $lastName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $firstName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $middleName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $address;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $city;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $state;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $zipCode;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $socialNumber;

    /**
     * @ORM\Column(type="boolean")
     */
    public $adult;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $phone;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $availableHours;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    public $weekAvailable;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    public $startDate;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $salary;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $availability;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $hasLicense;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $licenseNumber;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $licenseState;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $licenseExpiration;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $legalWorker;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $crime;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $crimeExplain;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $background;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $backExplain;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $yearsHighschool;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $hasDiploma;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $hasGED;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    public $schools;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $yearsCollege;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $collegeSchool;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $collegeState;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $major;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $degree;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $gpa;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $contactEmployers;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $employmentName;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    public $employers;

    /**
     * @ORM\Column(name="refs", type="json_array", nullable=true)
     */
    public $references;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $skills;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $bestFit;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\File(mimeTypes={ "application/pdf" })
     */
    public $cv;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\File(mimeTypes={ "application/pdf" })
     */
    public $coverLetter;

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


}