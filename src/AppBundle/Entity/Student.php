<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Student
 *
 * @ORM\Table(name="students")
 * @ORM\Entity
 */
class Student
{
    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=30, nullable=false)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="second_name", type="string", length=30, nullable=true)
     */
    private $secondName;

    /**
     * @var string
     *
     * @ORM\Column(name="faculty", type="string", length=20, nullable=false)
     */
    private $faculty;

    /**
     * @var string
     *
     * @ORM\Column(name="department", type="string", length=30, nullable=false)
     */
    private $department;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=6, nullable=false)
     */
    private $gender;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="date", nullable=false)
     */
    private $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_number", type="string", length=10, nullable=false)
     */
    private $contactNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="e_mail", type="string", length=50, nullable=true)
     */
    private $eMail;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=200, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=8)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Student
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
     * Set secondName
     *
     * @param string $secondName
     *
     * @return Student
     */
    public function setSecondName($secondName)
    {
        $this->secondName = $secondName;

        return $this;
    }

    /**
     * Get secondName
     *
     * @return string
     */
    public function getSecondName()
    {
        return $this->secondName;
    }

    /**
     * Set faculty
     *
     * @param string $faculty
     *
     * @return Student
     */
    public function setFaculty($faculty)
    {
        $this->faculty = $faculty;

        return $this;
    }

    /**
     * Get faculty
     *
     * @return string
     */
    public function getFaculty()
    {
        return $this->faculty;
    }

    /**
     * Set department
     *
     * @param string $department
     *
     * @return Student
     */
    public function setDepartment($department)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get department
     *
     * @return string
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return Student
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return Student
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set contactNumber
     *
     * @param string $contactNumber
     *
     * @return Student
     */
    public function setContactNumber($contactNumber)
    {
        $this->contactNumber = $contactNumber;

        return $this;
    }

    /**
     * Get contactNumber
     *
     * @return string
     */
    public function getContactNumber()
    {
        return $this->contactNumber;
    }

    /**
     * Set eMail
     *
     * @param string $eMail
     *
     * @return Student
     */
    public function setEMail($eMail)
    {
        $this->eMail = $eMail;

        return $this;
    }

    /**
     * Get eMail
     *
     * @return string
     */
    public function getEMail()
    {
        return $this->eMail;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Student
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
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
