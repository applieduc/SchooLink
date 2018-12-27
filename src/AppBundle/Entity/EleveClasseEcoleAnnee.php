<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EleveClasseEcoleAnnee
 *
 * @ORM\Table(name="eleve_classe_ecole_annee")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EleveClasseEcoleAnneeRepository")
 */
class EleveClasseEcoleAnnee
{


    public  function __construct()
    {
        $this->dateCreation=new \DateTime();
        $this->dateModification=new \DateTime();
        $this->archiver=0;
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Eleve",cascade={"persist"})
     *
     */
    private $eleve;

      /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Ecole",cascade={"persist"})
     *
     */
    private $ecole;

    /**
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Classe",cascade={"persist"})
     */
    private $classe;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Annee",cascade={"persist"})
     */
    private $annee;


      /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetimetz")
     */
    private $dateCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateModification", type="datetimetz")
     */
    private $dateModification;
     /**
     * @var boolean
     *
     * @ORM\Column(name="archiver", type="boolean")
     */
    private $archiver;


    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return EleveClasseEcoleAnnee
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;
    
        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set dateModification
     *
     * @param \DateTime $dateModification
     *
     * @return EleveClasseEcoleAnnee
     */
    public function setDateModification($dateModification)
    {
        $this->dateModification = $dateModification;
    
        return $this;
    }

    /**
     * Get dateModification
     *
     * @return \DateTime
     */
    public function getDateModification()
    {
        return $this->dateModification;
    }

    /**
     * Set archiver
     *
     * @param boolean $archiver
     *
     * @return EleveClasseEcoleAnnee
     */
    public function setArchiver($archiver)
    {
        $this->archiver = $archiver;
    
        return $this;
    }

    /**
     * Get archiver
     *
     * @return boolean
     */
    public function getArchiver()
    {
        return $this->archiver;
    }

    /**
     * Set eleve
     *
     * @param \AppBundle\Entity\Eleve $eleve
     *
     * @return EleveClasseEcoleAnnee
     */
    public function setEleve(\AppBundle\Entity\Eleve $eleve = null)
    {
        $this->eleve = $eleve;
    
        return $this;
    }

    /**
     * Get eleve
     *
     * @return \AppBundle\Entity\Eleve
     */
    public function getEleve()
    {
        return $this->eleve;
    }

    /**
     * Set ecole
     *
     * @param \AppBundle\Entity\Ecole $ecole
     *
     * @return EleveClasseEcoleAnnee
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
     * Set classe
     *
     * @param \AppBundle\Entity\Classe $classe
     *
     * @return EleveClasseEcoleAnnee
     */
    public function setClasse(\AppBundle\Entity\Classe $classe = null)
    {
        $this->classe = $classe;
    
        return $this;
    }

    /**
     * Get classe
     *
     * @return \AppBundle\Entity\Classe
     */
    public function getClasse()
    {
        return $this->classe;
    }

    /**
     * Set annee
     *
     * @param \AppBundle\Entity\Annee $annee
     *
     * @return EleveClasseEcoleAnnee
     */
    public function setAnnee(\AppBundle\Entity\Annee $annee = null)
    {
        $this->annee = $annee;
    
        return $this;
    }

    /**
     * Get annee
     *
     * @return \AppBundle\Entity\Annee
     */
    public function getAnnee()
    {
        return $this->annee;
    }
}
