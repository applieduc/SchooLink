<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Eleve
 *
 * @ORM\Table(name="eleve")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EleveRepository")
 */
class Eleve
{
   
    public  function __construct()
    {
        $this->dateCreation=new \DateTime();
        $this->dateModification=new \DateTime();
        $this->archiver=0;
        $this->photo="avatar.png";
        $this->code=strtoupper(substr(sha1(uniqid(mt_rand(), true)), 4, 6)."-ELV-" . substr(sha1(uniqid(mt_rand(), true)), 2, 4));
    }
/**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Ecole",cascade={"persist"}, inversedBy="eleves")
     *
     */
    private $ecole;

    /**
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Classe",cascade={"persist"})
     */
    private $classe;




    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

     /**
     * @var string
     *
     * @ORM\Column(name="contact", type="string", length=255)
     */
    private $contact;

    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=20)
     */
    private $sexe;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateNaissance", type="datetime")
     */
    private $dateNaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string")
     */
    private $photo;

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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Eleve
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
     * @return Eleve
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
     * Set sexe
     *
     * @param string $sexe
     *
     * @return Eleve
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get sexe
     *
     * @return string
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     *
     * @return Eleve
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return Eleve
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
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Eleve
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
     * @return Eleve
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
     * Set classe
     *
     * @param \AppBundle\Entity\Classe $classe
     *
     * @return Eleve
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
     * Set archiver
     *
     * @param boolean $archiver
     *
     * @return Eleve
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
     * Set ecole
     *
     * @param \AppBundle\Entity\Ecole $ecole
     *
     * @return Eleve
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
     * Set code
     *
     * @param string $code
     *
     * @return Eleve
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set contact
     *
     * @param string $contact
     *
     * @return Eleve
     */
    public function setContact($contact)
    {
        $this->contact = $contact;
    
        return $this;
    }

    /**
     * Get contact
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }
}
