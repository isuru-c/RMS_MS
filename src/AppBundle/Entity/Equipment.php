<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Equipment
 *
 * @ORM\Table(name="equipment", indexes={@ORM\Index(name="equipment_category", columns={"equipment_category_id"})})
 * @ORM\Entity
 */
class Equipment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="available", type="integer", nullable=false)
     */
    private $available;

    /**
     * @var integer
     *
     * @ORM\Column(name="reserved", type="integer", nullable=false)
     */
    private $reserved;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\EquipmentCategory
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\EquipmentCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="equipment_category_id", referencedColumnName="id")
     * })
     */
    private $equipmentCategory;



    /**
     * Set available
     *
     * @param integer $available
     *
     * @return Equipment
     */
    public function setAvailable($available)
    {
        $this->available = $available;

        return $this;
    }

    /**
     * Get available
     *
     * @return integer
     */
    public function getAvailable()
    {
        return $this->available;
    }

    /**
     * Set reserved
     *
     * @param integer $reserved
     *
     * @return Equipment
     */
    public function setReserved($reserved)
    {
        $this->reserved = $reserved;

        return $this;
    }

    /**
     * Get reserved
     *
     * @return integer
     */
    public function getReserved()
    {
        return $this->reserved;
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
     * Set equipmentCategory
     *
     * @param \AppBundle\Entity\EquipmentCategory $equipmentCategory
     *
     * @return Equipment
     */
    public function setEquipmentCategory(\AppBundle\Entity\EquipmentCategory $equipmentCategory = null)
    {
        $this->equipmentCategory = $equipmentCategory;

        return $this;
    }

    /**
     * Get equipmentCategory
     *
     * @return \AppBundle\Entity\EquipmentCategory
     */
    public function getEquipmentCategory()
    {
        return $this->equipmentCategory;
    }
}
