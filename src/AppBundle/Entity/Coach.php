<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Coach
 *
 * @ORM\Table(name="coach", indexes={@ORM\Index(name="sport_id", columns={"sport_id"})})
 * @ORM\Entity
 */
class Coach
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
     * @var integer
     *
     * @ORM\Column(name="sport_id", type="integer", nullable=false)
     */
    private $sportId;

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
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Coach
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
     * @return Coach
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
     * Set sportId
     *
     * @param integer $sportId
     *
     * @return Coach
     */
    public function setSportId($sportId)
    {
        $this->sportId = $sportId;

        return $this;
    }

    /**
     * Get sportId
     *
     * @return integer
     */
    public function getSportId()
    {
        return $this->sportId;
    }

    /**
     * Set contactNumber
     *
     * @param string $contactNumber
     *
     * @return Coach
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
     * @return Coach
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
     * @return Coach
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
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
