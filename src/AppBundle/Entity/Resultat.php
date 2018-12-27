<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Resultat
 *
 * @ORM\Table(name="resultat")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ResultatRepository")
 */
class Resultat
{

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TypeClasse",cascade={"persist"})
     */
    private $type_classe;


    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Annee",cascade={"persist"})
     */
    private $annee;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Eleve",cascade={"persist"})
     */
    private $eleve;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var int
     *
     * @ORM\Column(name="coeff", type="integer")
     */
    private $coeff;

    /**
     * @var int
     *
     * @ORM\Column(name="rang1", type="integer",nullable=true)
     */
    private $rang1;

    /**
     * @var int
     *
     * @ORM\Column(name="rang2", type="integer",nullable=true)
     */
    private $rang2;

    /**
     * @var int
     *
     * @ORM\Column(name="rang3", type="integer",nullable=true)
     */
    private $rang3;

    /**
     * @var int
     *
     * @ORM\Column(name="rangAnnuelle", type="integer",nullable=true)
     */
    private $rangAnnuelle;


    /**
     * @var string
     *
     * @ORM\Column(name="obs", type="integer",nullable=true)
     */
    private $obs;


    /**
     * @var float
     *
     * @ORM\Column(name="moyGen1", type="float")
     */
    private $moyGen1;

    /**
     * @var float
     *
     * @ORM\Column(name="moyGen2", type="float", nullable=true)
     */
    private $moyGen2;

    /**
     * @var float
     *
     * @ORM\Column(name="moyGen3", type="float", nullable=true)
     */
    private $moyGen3;

    /**
     * @var float
     *
     * @ORM\Column(name="moyInt3", type="float", nullable=true)
     */
    private $moyAnnuelle;


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
     * Set moyInt1
     *
     * @param float $moyInt1
     *
     * @return Resultat
     */
    public function setMoyInt1($moyInt1)
    {
        $this->moyInt1 = $moyInt1;
    
        return $this;
    }

    /**
     * Set coeff
     *
     * @param integer $coeff
     *
     * @return Resultat
     */
    public function setCoeff($coeff)
    {
        $this->coeff = $coeff;
    
        return $this;
    }

    /**
     * Get coeff
     *
     * @return integer
     */
    public function getCoeff()
    {
        return $this->coeff;
    }

    /**
     * Set moyGen1
     *
     * @param float $moyGen1
     *
     * @return Resultat
     */
    public function setMoyGen1($moyGen1)
    {
        $this->moyGen1 = $moyGen1;
    
        return $this;
    }

    /**
     * Get moyGen1
     *
     * @return float
     */
    public function getMoyGen1()
    {
        return $this->moyGen1;
    }

    /**
     * Set moyGen2
     *
     * @param float $moyGen2
     *
     * @return Resultat
     */
    public function setMoyGen2($moyGen2)
    {
        $this->moyGen2 = $moyGen2;
    
        return $this;
    }

    /**
     * Get moyGen2
     *
     * @return float
     */
    public function getMoyGen2()
    {
        return $this->moyGen2;
    }

    /**
     * Set moyGen3
     *
     * @param float $moyGen3
     *
     * @return Resultat
     */
    public function setMoyGen3($moyGen3)
    {
        $this->moyGen3 = $moyGen3;
    
        return $this;
    }

    /**
     * Get moyGen3
     *
     * @return float
     */
    public function getMoyGen3()
    {
        return $this->moyGen3;
    }
    
    /**
     * Set eleve
     *
     * @param \AppBundle\Entity\Eleve $eleve
     *
     * @return Resultat
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
     * Set rang1
     *
     * @param integer $rang1
     *
     * @return Resultat
     */
    public function setRang1($rang1)
    {
        $this->rang1 = $rang1;
    
        return $this;
    }

    /**
     * Get rang1
     *
     * @return integer
     */
    public function getRang1()
    {
        return $this->rang1;
    }

    /**
     * Set rang2
     *
     * @param integer $rang2
     *
     * @return Resultat
     */
    public function setRang2($rang2)
    {
        $this->rang2 = $rang2;
    
        return $this;
    }

    /**
     * Get rang2
     *
     * @return integer
     */
    public function getRang2()
    {
        return $this->rang2;
    }

    /**
     * Set rang3
     *
     * @param integer $rang3
     *
     * @return Resultat
     */
    public function setRang3($rang3)
    {
        $this->rang3 = $rang3;
    
        return $this;
    }

    /**
     * Get rang3
     *
     * @return integer
     */
    public function getRang3()
    {
        return $this->rang3;
    }

    /**
     * Set obs
     *
     * @param integer $obs
     *
     * @return Resultat
     */
    public function setObs($obs)
    {
        $this->obs = $obs;
    
        return $this;
    }

    /**
     * Get obs
     *
     * @return integer
     */
    public function getObs()
    {
        return $this->obs;
    }

    /**
     * Set moyAnnuelle
     *
     * @param float $moyAnnuelle
     *
     * @return Resultat
     */
    public function setMoyAnnuelle($moyAnnuelle)
    {
        $this->moyAnnuelle = $moyAnnuelle;
    
        return $this;
    }

    /**
     * Get moyAnnuelle
     *
     * @return float
     */
    public function getMoyAnnuelle()
    {
        return $this->moyAnnuelle;
    }

    /**
     * Set rangAnnuelle
     *
     * @param integer $rangAnnuelle
     *
     * @return Resultat
     */
    public function setRangAnnuelle($rangAnnuelle)
    {
        $this->rangAnnuelle = $rangAnnuelle;
    
        return $this;
    }

    /**
     * Get rangAnnuelle
     *
     * @return integer
     */
    public function getRangAnnuelle()
    {
        return $this->rangAnnuelle;
    }

    /**
     * Set typeClasse
     *
     * @param \AppBundle\Entity\TypeClasse $typeClasse
     *
     * @return Resultat
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
     * Set annee
     *
     * @param \AppBundle\Entity\Annee $annee
     *
     * @return Resultat
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