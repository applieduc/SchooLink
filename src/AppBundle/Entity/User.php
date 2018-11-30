<?php
/**
 * Created by PhpStorm.
 * User: dodji
 * Date: 25/11/18
 * Time: 06:17
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discr", type="string")
* @DiscriminatorMap({"censeur" = "Censeur", "professeur" = "Professeur", "parent"="Parents", "adminGen" ="AdminGen"})
 */
abstract class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;



}
