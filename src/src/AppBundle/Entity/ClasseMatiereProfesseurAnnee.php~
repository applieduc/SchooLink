<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClasseMatiereProfesseurAnnee
 *
 * @ORM\Table(name="classe_matiere_professeur_annee")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClasseMatiereProfesseurAnneeRepository")
 */
class ClasseMatiereProfesseurAnnee
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ClasseMatiere",cascade={"persist"})
     */
    private $classe_matiere;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Professeur",cascade={"persist"})
     */
    private $professeur;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Annee",cascade={"persist"})
     */
    private $annee;

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
     * Set classeMatiere
     *
     * @param \AppBundle\Entity\ClasseMatiere $classeMatiere
     *
     * @return ClasseMatiereProfesseurAnnee
     */
    public function setClasseMatiere(\AppBundle\Entity\ClasseMatiere $classeMatiere = null)
    {
        $this->classe_matiere = $classeMatiere;

        return $this;
    }

    /**
     * Get classeMatiere
     *
     * @return \AppBundle\Entity\ClasseMatiere
     */
    public function getClasseMatiere()
    {
        return $this->classe_matiere;
    }

    /**
     * Set professeur
     *
     * @param \AppBundle\Entity\Professeur $professeur
     *
     * @return ClasseMatiereProfesseurAnnee
     */
    public function setProfesseur(\AppBundle\Entity\Professeur $professeur = null)
    {
        $this->professeur = $professeur;

        return $this;
    }

    /**
     * Get professeur
     *
     * @return \AppBundle\Entity\Professeur
     */
    public function getProfesseur()
    {
        return $this->professeur;
    }

    /**
     * Set annee
     *
     * @param \AppBundle\Entity\Annee $annee
     *
     * @return ClasseMatiereProfesseurAnnee
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
