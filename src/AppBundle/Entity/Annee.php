<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;

/**
 * Annee
 *
 * @ORM\Table(name="annee")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AnneeRepository")
 */
class Annee
{
    /**
    * @var int
    *
    * @ORM\Column(name="id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Ecole",cascade={"persist"})
     *
     */
    private $ecole;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebut", type="date")
     */
    private $dateDebut;


    /**
     * @var bool
     *
     * @ORM\Column(name="cloture", type="boolean")
     */
    private $cloture;

    /**
     * @var string
     *
     * @ORM\Column(name="typePeriode", type="string", length=255)
     */
    private $typePeriode;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="date")
     */
    private $dateFin;



     /**
     * Set cloture
     *
     * @param boolean $cloture
     *
     * @return Annee
     */
    public function setCloture($cloture)
    {
        $this->cloture = $cloture;
    
        return $this;
    }

    /**
     * Get cloture
     *
     * @return boolean
     */
    public function getCloture()
    {
        return $this->cloture;
    }

    /**
     * Set typePeriode
     *
     * @param string $typePeriode
     *
     * @return Annee
     */
    public function setTypePeriode($typePeriode)
    {
        $this->typePeriode = $typePeriode;
    
        return $this;
    }

    /**
     * Get typePeriode
     *
     * @return string
     */
    public function getTypePeriode()
    {
        return $this->typePeriode;
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
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Annee
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
    
        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Annee
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
    
        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * Set ecole
     *
     * @param \AppBundle\Entity\Ecole $ecole
     *
     * @return Annee
     */
    public function setEcole(\AppBundle\Entity\Ecole $ecole = null)
    {
        $this->ecole = $ecole;
    
        return $this;
    }

    /**
     * Get ecole
     *
     * @return \AppBundle\Entity\Ecole
     */
    public function getEcole()
    {
        return $this->ecole;
    }
}
