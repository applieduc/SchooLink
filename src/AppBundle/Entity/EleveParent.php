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


}

