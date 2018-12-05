<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\EntityListener;


use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Description of AppEntityListener
 *
 * @author crrs
 */
class AppEntityListener
{

    private $tokenStorage;

    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function prePersist($entity)
    {
        if (@property_exists($entity, "createdAt")) {
            $entity->setCreatedAt(new \DateTime());

            $token = $this->tokenStorage->getToken();

            if ($token && $this->tokenStorage->getToken()->getUser() instanceof User) {
                $entity->setCreatedBy($this->tokenStorage->getToken()->getUser());
            }
        }

    }

    public function preFlush($entity)
    {
        if (@property_exists($entity, "updatedAt")) {
            $entity->setUpdatedAt(new \DateTime());

            $token = $this->tokenStorage->getToken();

            if ($token && $this->tokenStorage->getToken()->getUser() instanceof User) {
                $entity->setUpdatedBy($this->tokenStorage->getToken()->getUser());
            }
        }
    }
}
