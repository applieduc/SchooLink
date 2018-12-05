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
}

