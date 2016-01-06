<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EquipmentCategory
 *
 * @ORM\Table(name="equipment_category", indexes={@ORM\Index(name="sport", columns={"sport_id"})})
 * @ORM\Entity
 */
class EquipmentCategory
{
    /**
     * @var integer
     *
     * @ORM\Column(name="lend_time", type="integer", nullable=false)
     */
    private $lendTime;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Sport
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Sport")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sport_id", referencedColumnName="id")
     * })
     */
    private $sport;



    /**
     * Set lendTime
     *
     * @param integer $lendTime
     *
     * @return EquipmentCategory
     */
    public function setLendTime($lendTime)
    {
        $this->lendTime = $lendTime;

        return $this;
    }

    /**
     * Get lendTime
     *
     * @return integer
     */
    public function getLendTime()
    {
        return $this->lendTime;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return EquipmentCategory
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * Set sport
     *
     * @param \AppBundle\Entity\Sport $sport
     *
     * @return EquipmentCategory
     */
    public function setSport(\AppBundle\Entity\Sport $sport = null)
    {
        $this->sport = $sport;

        return $this;
    }

    /**
     * Get sport
     *
     * @return \AppBundle\Entity\Sport
     */
    public function getSport()
    {
        return $this->sport;
    }
}
