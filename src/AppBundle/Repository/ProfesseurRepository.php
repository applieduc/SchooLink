<?php

namespace AppBundle\Repository;

/**
 * ProfesseurRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProfesseurRepository extends \Doctrine\ORM\EntityRepository
{
    public function EtablisementProf(){
        return $this->createQueryBuilder('p')
            ->addSelect('p.id')
            ->addSelect('p.codeProf')
            ->addSelect('ann')

            ->addSelect('e')
            ->addSelect('cm')
            ->addSelect('cl')
            ->addSelect('mat')
           ->addSelect('ec')
           ->leftJoin('p.enseignement','e')
            ->leftJoin('e.annee','ann')
            ->leftJoin('e.classe_matiere','cm')
            ->leftJoin('cm.matiere','mat')
            ->leftJoin('cm.classe','cl')
            ->leftJoin('cl.ecole','ec')
            ->where('p.id = 3')
            ->getQuery()
            ->getResult()
            ;
    }

   // public function Recher
}
