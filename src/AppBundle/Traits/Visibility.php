<?php

namespace AppBundle\Traits;

trait Visibility{

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=true, options={"default": true} )
     * @Form\Display("Active")
     *
     */
    private $active;

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

}