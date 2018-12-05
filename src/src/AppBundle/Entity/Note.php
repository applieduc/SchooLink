<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Note
 *
 * @ORM\Table(name="note")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NoteRepository")
 */
class Note
{

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Eleve",cascade={"persist"})
     */
    private $eleve;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Periode",cascade={"persist"})
     */
    private $periode;




    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ClasseMatiereProfesseurAnnee",cascade={"persist"})
     */
    private $classe_matiere_professeur_annee;


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
     * @ORM\Column(name="type", type="string")
     *
     */
    private $type;


    /**
     * @var int
     *
     * @ORM\Column(name="note", type="integer")
     *
     */
    private $note;


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
     * @var \DateTime
     *
     * @ORM\Column(name="dateValidation", type="datetimetz",nullable=true)
     */
    private $dateValidation;


    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string")
     */
    private $statut;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}

