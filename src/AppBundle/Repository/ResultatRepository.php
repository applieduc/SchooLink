<?php

namespace AppBundle\Repository;

/**
 * NoteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ResultatRepository extends \Doctrine\ORM\EntityRepository
{

    public function typeInterro($id)
    {
        $con=$this->_em->getConnection();
        $state=$con->prepare('select distinct type from note where classe_matiere_professeur_annee_id=? and statut ="validé" and etat=1 ');
        $state->execute(array($id));

        return $state->fetchAll();
    }
    public function typeR($id)
    {
        $con=$this->_em->getConnection();
        $state=$con->prepare('select distinct type from note where classe_matiere_professeur_annee_id=? and statut ="non validé" ');
        $state->execute(array($id));

        return $state->fetchAll();
    }


    public function getNV($id,$type)
    {
        $con=$this->_em->getConnection();
        $state=$con->prepare('select  * from note where classe_matiere_professeur_annee_id=? and statut="mise en attente" and type=?');
        $state->execute(array($id, $type));

        return $state->fetchAll();
    }
}
