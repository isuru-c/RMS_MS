<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EquipmentLend
 *
 * @ORM\Table(name="equipment_lend", indexes={@ORM\Index(name="equipment_reserve_id", columns={"equipment_reserve_id"})})
 * @ORM\Entity
 */
class EquipmentLend
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lend_date", type="date", nullable=false)
     */
    private $lendDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="due_date", type="date", nullable=false)
     */
    private $dueDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="state", type="integer", nullable=false)
     */
    private $state;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="returned_date", type="date", nullable=true)
     */
    private $returnedDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\EquipmentReserve
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\EquipmentReserve")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="equipment_reserve_id", referencedColumnName="id")
     * })
     */
    private $equipmentReserve;



    /**
     * Set lendDate
     *
     * @param \DateTime $lendDate
     *
     * @return EquipmentLend
     */
    public function setLendDate($lendDate)
    {
        $this->lendDate = $lendDate;

        return $this;
    }

    /**
     * Get lendDate
     *
     * @return \DateTime
     */
    public function getLendDate()
    {
        return $this->lendDate;
    }

    /**
     * Set dueDate
     *
     * @param \DateTime $dueDate
     *
     * @return EquipmentLend
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    /**
     * Get dueDate
     *
     * @return \DateTime
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * Set state
     *
     * @param integer $state
     *
     * @return EquipmentLend
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
     * Set returnedDate
     *
     * @param \DateTime $returnedDate
     *
     * @return EquipmentLend
     */
    public function setReturnedDate($returnedDate)
    {
        $this->returnedDate = $returnedDate;

        return $this;
    }

    /**
     * Get returnedDate
     *
     * @return \DateTime
     */
    public function getReturnedDate()
    {
        return $this->returnedDate;
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
     * Set equipmentReserve
     *
     * @param \AppBundle\Entity\EquipmentReserve $equipmentReserve
     *
     * @return EquipmentLend
     */
    public function setEquipmentReserve(\AppBundle\Entity\EquipmentReserve $equipmentReserve = null)
    {
        $this->equipmentReserve = $equipmentReserve;

        return $this;
    }

    /**
     * Get equipmentReserve
     *
     * @return \AppBundle\Entity\EquipmentReserve
     */
    public function getEquipmentReserve()
    {
        return $this->equipmentReserve;
    }
}
