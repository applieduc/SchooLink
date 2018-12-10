<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Traits\Identifiers;
/**
 * Professeur
 *
 * @ORM\Table(name="professeur")
 * @ORM\EntityListeners("AppBundle\EntityListener\AppEntityListener")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProfesseurRepository")
 */
class Professeur
{
    use Identifiers;
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
     * @ORM\Column(name="telephone", type="string", length=255)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;
    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ClasseMatiereProfesseurAnnee", mappedBy="professeur",cascade={"persist"})
     */
    private $enseignement;

    /**
     * @var string
     *
     */
    private $Identite;

    public function __construct()
    {
        $this->enseignement = new \Doctrine\Common\Collections\ArrayCollection();
    }




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
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param string $telephone
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    /**
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param string $adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }



    public function getIdentite(){

        return strtoupper($this->nom) . " " . $this->prenom;
    }



    /**
     * Add detailsFiche
     *
     * @param \AppBundle\Entity\ClasseMatiereProfesseurAnnee $enseignement
     *
     * @return mixed
     */
    public function addEnseignement(\AppBundle\Entity\ClasseMatiereProfesseurAnnee $enseignement)
    {
        $this->enseignement[] = $enseignement;

        return $this;
    }

    /**
     * Remove detailsFiche
     *
     * @param \AppBundle\Entity\ClasseMatiereProfesseurAnnee $enseignement
     */
    public function removeEnseignement(\AppBundle\Entity\ClasseMatiereProfesseurAnnee $enseignement)
    {
        $this->enseignement->removeElement($enseignement);
    }

    /**
     * Get detailsFiche
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEnseignement()
    {
        return $this->enseignement;
    }



}

