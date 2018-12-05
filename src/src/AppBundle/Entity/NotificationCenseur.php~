<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NotificationProfesseur
 *
 * @ORM\Table(name="notification_censeur")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NotificationCenseurRepository")
 */
class NotificationCenseur
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
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @var bool
     *
     * @ORM\Column(name="statut", type="boolean")
     */
    private $statut;


    /**
     * @var int
     *
     * @ORM\Column(name="emetteur", type="integer")
     */
    private $emetteur;


    /**
     * @var int
     *
     * @ORM\Column(name="destinataire", type="integer")
     */
    private $destinataire;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetimetz")
     */
    private $dateCreation;


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
     * Set message
     *
     * @param string $message
     *
     * @return NotificationProfesseur
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set statut
     *
     * @param boolean $statut
     *
     * @return NotificationProfesseur
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return bool
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return NotificationProfesseur
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
     * Set emetteur
     *
     * @param integer $emetteur
     *
     * @return NotificationCenseur
     */
    public function setEmetteur($emetteur)
    {
        $this->emetteur = $emetteur;

        return $this;
    }

    /**
     * Get emetteur
     *
     * @return integer
     */
    public function getEmetteur()
    {
        return $this->emetteur;
    }

    /**
     * Set destinataire
     *
     * @param integer $destinataire
     *
     * @return NotificationCenseur
     */
    public function setDestinataire($destinataire)
    {
        $this->destinataire = $destinataire;

        return $this;
    }

    /**
     * Get destinataire
     *
     * @return integer
     */
    public function getDestinataire()
    {
        return $this->destinataire;
    }
}
