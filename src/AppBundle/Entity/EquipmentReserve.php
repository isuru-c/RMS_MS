<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EquipmentReserve
 *
 * @ORM\Table(name="equipment_reserve", indexes={@ORM\Index(name="student_id", columns={"student_id", "equipment_id"}), @ORM\Index(name="equipment_id", columns={"equipment_id"}), @ORM\Index(name="IDX_699B25CDCB944F1A", columns={"student_id"})})
 * @ORM\Entity
 */
class EquipmentReserve
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="reserved_date", type="date", nullable=false)
     */
    private $reservedDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="state", type="integer", nullable=false)
     */
    private $state;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Equipment
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Equipment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="equipment_id", referencedColumnName="id")
     * })
     */
    private $equipment;

    /**
     * @var \AppBundle\Entity\Student
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Student")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     * })
     */
    private $student;



    /**
     * Set reservedDate
     *
     * @param \DateTime $reservedDate
     *
     * @return EquipmentReserve
     */
    public function setReservedDate($reservedDate)
    {
        $this->reservedDate = $reservedDate;

        return $this;
    }

    /**
     * Get reservedDate
     *
     * @return \DateTime
     */
    public function getReservedDate()
    {
        return $this->reservedDate;
    }

    /**
     * Set state
     *
     * @param integer $state
     *
     * @return EquipmentReserve
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return integer
     */
    public function getState()
    {
        return $this->state;
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
     * Set equipment
     *
     * @param \AppBundle\Entity\Equipment $equipment
     *
     * @return EquipmentReserve
     */
    public function setEquipment(\AppBundle\Entity\Equipment $equipment = null)
    {
        $this->equipment = $equipment;

        return $this;
    }

    /**
     * Get equipment
     *
     * @return \AppBundle\Entity\Equipment
     */
    public function getEquipment()
    {
        return $this->equipment;
    }

    /**
     * Set students
     *
     * @param \AppBundle\Entity\Student $student
     *
     * @return EquipmentReserve
     */
    public function setStudent(\AppBundle\Entity\Student $student = null)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get students
     *
     * @return \AppBundle\Entity\Student
     */
    public function getStudent()
    {
        return $this->student;
    }
}
