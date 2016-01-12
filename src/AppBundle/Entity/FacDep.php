<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FacDep
 *
 * @ORM\Table(name="fac_dep")
 * @ORM\Entity
 */
class FacDep
{
    /**
     * @var string
     *
     * @ORM\Column(name="faculty", type="string", length=20)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $faculty;

    /**
     * @var string
     *
     * @ORM\Column(name="department", type="string", length=40)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $department;



    /**
     * Set faculty
     *
     * @param string $faculty
     *
     * @return FacDep
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
     * @return FacDep
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
}
