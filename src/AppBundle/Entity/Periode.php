<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Periode
 *
 * @ORM\Table(name="periode")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PeriodeRepository")
 */
class Periode
{

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Annee",cascade={"persist"})
     */
    private $annee;

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
     * @ORM\Column(name="date_debut", type="datetimetz")
     */
    private $dateDebut;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_periode", type="string")
     */
    private $nom_periode;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="datetimetz")
     */
    private $dateFin;


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
     * Set type
     *
     * @param string $type
     *
     * @return Periode
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Periode
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
     * @return Periode
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
     * Set annee
     *
     * @param \AppBundle\Entity\Annee $annee
     *
     * @return Periode
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

    /**
     * Set nomPeriode
     *
     * @param string $nomPeriode
     *
     * @return Periode
     */
    public function setNomPeriode($nomPeriode)
    {
        $this->nom_periode = $nomPeriode;
    
        return $this;
    }

    /**
     * Get nomPeriode
     *
     * @return string
     */
    public function getNomPeriode()
    {
        return $this->nom_periode;
    }
}
