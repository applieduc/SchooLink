<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EleveParent
 *
 * @ORM\Table(name="eleve_parent")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EleveParentRepository")
 */
class EleveParent
{
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Eleve",cascade={"persist"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $eleve;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Parents",cascade={"persist"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $parent;



    /**
     * Set eleve
     *
     * @param \AppBundle\Entity\Eleve $eleve
     *
     * @return EleveParent
     */
    public function setEleve(\AppBundle\Entity\Eleve $eleve)
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
     * Set parent
     *
     * @param \AppBundle\Entity\Parents $parent
     *
     * @return EleveParent
     */
    public function setParent(\AppBundle\Entity\Parents $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\Parents
     */
    public function getParent()
    {
        return $this->parent;
    }
}
