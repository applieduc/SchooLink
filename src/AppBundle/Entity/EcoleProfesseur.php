<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EcoleProfesseur
 *
 * @ORM\Table(name="ecole_professeur")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EcoleProfesseurRepository")
 */
class EcoleProfesseur
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Ecole", inversedBy="ecoleProf")
     */
    private $ecole;
     /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Professeur")
     */
    private $professeur;


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
     * Set ecole
     *
     * @param \AppBundle\Entity\Ecole $ecole
     *
     * @return EcoleProfesseur
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

    /**
     * Set professeur
     *
     * @param \AppBundle\Entity\Professeur $professeur
     *
     * @return EcoleProfesseur
     */
    public function setProfesseur(\AppBundle\Entity\Professeur $professeur = null)
    {
        $this->professeur = $professeur;
    
        return $this;
    }

    /**
     * Get professeur
     *
     * @return \AppBundle\Entity\Professeur
     */
    public function getProfesseur()
    {
        return $this->professeur;
    }
}
