<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EcoleProfesseur
 *
 * @ORM\Table(name="ecole_professeur")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EcoleProfesseurRepository")
 */
class EcoleProfesseur
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Ecole", inversedBy="ecoleProf")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ecole;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Professeur", inversedBy="profecole",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $professeur;

    /**
     * @ORM\Column(type="integer")
     */


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
     * @return mixed
     */
    public function getEcole()
    {
        return $this->ecole;
    }

    /**
     * @param mixed $ecole
     */
    public function setEcole($ecole)
    {
        $this->ecole = $ecole;
    }

    /**
     * @return mixed
     */
    public function getProfesseur()
    {
        return $this->professeur;
    }

    /**
     * @param mixed $professeur
     */
    public function setProfesseur($professeur)
    {
        $this->professeur = $professeur;
    }

}

