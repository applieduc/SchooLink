<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Professeur
 *
 * @ORM\Table(name="professeur")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProfesseurRepository")
 */
class Professeur extends User
{

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Compte",cascade={"persist"})
     *
     */
    private $compte;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @var string
     *
     * @ORM\Column(name="code_prof", type="string", length=255)
     */
    private $codeProf;




    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string",nullable=true)
     */
    private $photo;
    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255,nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255,nullable=true)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=255,nullable=true)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255,nullable=true)
     */
    private $adresse;


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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set codeProf
     *
     * @param string $codeProf
     *
     * @return Professeur
     */
    public function setCodeProf($codeProf)
    {
        $this->codeProf = $codeProf;

        return $this;
    }

    /**
     * Get codeProf
     *
     * @return string
     */
    public function getCodeProf()
    {
        return $this->codeProf;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Professeur
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
     * @return Professeur
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
     * Set photo
     *
     * @param string $photo
     *
     * @return Professeur
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    
        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Professeur
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Professeur
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    
        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return Professeur
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    
        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Professeur
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    
        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set compte
     *
     * @param \AppBundle\Entity\Compte $compte
     *
     * @return Professeur
     */
    public function setCompte(\AppBundle\Entity\Compte $compte = null)
    {
        $this->compte = $compte;
    
        return $this;
    }

    /**
     * Get compte
     *
     * @return \AppBundle\Entity\Compte
     */
    public function getCompte()
    {
        return $this->compte;
    }
}
