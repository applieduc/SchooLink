<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EleveTypeClasse
 *
 * @ORM\Table(name="eleve_type_classe")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EleveTypeClasseRepository")
 */
class EleveTypeClasse
{


    public  function __construct()
    {
        $this->dateCreation=new \DateTime();
        $this->dateModification=new \DateTime();
        $this->archiver=0;
    }


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Eleve",cascade={"persist"})
     */
    private $eleve;


  /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TypeClasse",cascade={"persist"})
     */
    private $type_classe;



    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return EleveTypeClasse
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
     * @return EleveTypeClasse
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
     * Set eleve
     *
     * @param \AppBundle\Entity\Eleve $eleve
     *
     * @return EleveTypeClasse
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
     * Set typeClasse
     *
     * @param \AppBundle\Entity\TypeClasse $typeClasse
     *
     * @return EleveTypeClasse
     */
    public function setTypeClasse(\AppBundle\Entity\TypeClasse $typeClasse = null)
    {
        $this->type_classe = $typeClasse;
    
        return $this;
    }

    /**
     * Get typeClasse
     *
     * @return \AppBundle\Entity\TypeClasse
     */
    public function getTypeClasse()
    {
        return $this->type_classe;
    }

    /**
     * Set archiver
     *
     * @param boolean $archiver
     *
     * @return EleveTypeClasse
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
}
