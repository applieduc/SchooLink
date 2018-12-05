<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Traits\Identifiers;
use Doctrine\ORM\Mapping\OneToOne;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ecole
 *
 * @ORM\Table(name="ecole")
 * @ORM\EntityListeners("AppBundle\EntityListener\AppEntityListener")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EcoleRepository")
 */
class Ecole
{
    use Identifiers;
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
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=255)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="code_telephone", type="string", length=255)
     */
    private $codeTelephone;

    /**
     * @var string
     * @var string
     * @Assert\File(mimeTypes={ "image/jpeg", "image/png", "image/jpg" })
     *
     * @ORM\Column(name="logo", type="string",nullable=true)
     */
    private $logo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", length=255)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime", length=255)
     */
    private $updatedAt;

    /**
     *
     * @OneToOne(targetEntity="Censeur", mappedBy="ecole")
     *
     */
     private $censeur;

    /**
     * Ecole constructor.
     */
    public function __construct()
    {
        $this->codeTelephone = 'ab';
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Ecole
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
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Ecole
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse4
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return Ecole
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
     * Set mail
     *
     * @param string $mail
     *
     * @return Ecole
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set codeTelephone
     *
     * @param string $codeTelephone
     *
     * @return Ecole
     */
    public function setCodeTelephone($codeTelephone)
    {
        $this->codeTelephone = $codeTelephone;

        return $this;
    }

    /**
     * Get codeTelephone
     *
     * @return string
     */
    public function getCodeTelephone()
    {
        return $this->codeTelephone;
    }

    /**
     * Set logo
     *
     * @param string $logo
     *
     * @return Ecole
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }



    /**
     * Set censeur
     *
     * @param \AppBundle\Entity\Censeur $censeur
     *
     * @return Ecole
     */
    public function setCenseur(\AppBundle\Entity\Censeur $censeur = null)
    {
        $this->censeur = $censeur;

        return $this;
    }

    /**
     * Get censeur
     *
     * @return \AppBundle\Entity\Censeur
     */
    public function getCenseur()
    {
        return $this->censeur;
    }
}
