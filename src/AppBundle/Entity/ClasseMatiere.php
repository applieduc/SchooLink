<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClasseMatiere
 *
 * @ORM\Table(name="classe_matiere")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClasseMatiereRepository")
 */
class ClasseMatiere
{


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Classe",cascade={"persist"})
     */
    private $classe;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Matiere",cascade={"persist"})
     */
    private $matiere;


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
     * @ORM\Column(name="coefficient", type="integer")
     *
     */
    private $coefficient;


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

