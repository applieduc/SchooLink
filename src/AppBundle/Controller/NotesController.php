<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Annee;
use AppBundle\Entity\Censeur;
use AppBundle\Entity\Classe;
use AppBundle\Entity\ClasseMatiere;
use AppBundle\Entity\ClasseMatiereProfesseurAnnee;
use AppBundle\Entity\Ecole;
use AppBundle\Entity\Eleve;
use AppBundle\Entity\EleveTypeClasse;
use AppBundle\Entity\Note;
use AppBundle\Entity\Periode;
use AppBundle\Entity\Professeur;
use AppBundle\Entity\TypeClasse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Eleve controller.
 *
 * @Route("notes")
 */
class NotesController extends Controller
{
    /**
     * Lists all notes entities.
     *
     * @Route("/{id}", name="notes_index")
     * @Method("GET")
     */
    public function indexAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $cm=$em->getRepository(ClasseMatiereProfesseurAnnee::class)->findAll();
if ($id==1)
{
    return $this->render('AppBundle:Notes:index.html.twig', array(

        'noteNV'=>$this->noteNonValide(),
        'fiche'=>$this->fiche($cm[0]->getTypeClasse(),$cm[0]->getClasseMatiere(),1)
    ));

}else{
    return $this->render('AppBundle:Notes:index.html.twig', array(
        'noteR'=>$this->noteRejet   (),
        'fiche'=>$this->fiche($cm[0]->getTypeClasse(),$cm[0]->getClasseMatiere(),1)
    ));
}

    }

    private function  noteNonValide()
    {
        $em=$this->getDoctrine()->getManager();
        $cm=$em->getRepository(ClasseMatiereProfesseurAnnee::class)->findAll();
        $tabNAV=array();
        $k=0;
        for ($i=0;$i<sizeof($cm);$i++)
        {
            $typeNV=$em->getRepository(Note::class)->typeNV($cm[$i]->getId());

            if($typeNV != null)
            {


                $tabNAV[$k]['annee']=$cm[$i]->getAnnee()->getDateDebut()->format("d-m-Y H:i:s").'-'.$cm[$i]->getAnnee()->getDateFin()->format("d-m-Y H:i:s");
                $tabNAV[$k]['professeur']=$cm[$i]->getProfesseur()->getNom().'-'.$cm[$i]->getProfesseur()->getPrenom();
                $tabNAV[$k]['classe']=$cm[$i]->getTypeClasse()->getClasse()->getLibelle().' '.$cm[$i]->getTypeClasse()->getLibelle();
                $tabNAV[$k]['matiere']=$cm[$i]->getClasseMatiere()->getMatiere()->getLibelle();
                $tabNAV[$k]['coefficient']=$cm[$i]->getClasseMatiere()->getCoefficient();
                $tabNAV[$k]['effectif']=sizeof($em->getRepository(EleveTypeClasse::class)->findBy(array('type_classe'=>$cm[$i]->getTypeClasse()->getId())));
                $tabNAV[$k]['idCm']=$cm[$i]->getId();

                for ($j=0;$j<sizeof($typeNV);$j++)
                {
                    $note=$em->getRepository(Note::class)->findBy(array('classe_matiere_professeur_annee'=>$cm[$i]->getId(),'type'=>$typeNV[$j]['type']));

                    $tabNAV[$k]['note'][$j]=$note;
                    //    $tabNAV[$k]['notetype']['type']=$typeNV[$j]['type'];

                    // if($i==0)$tabNAV[$k]['periode']=$note[0]->getPeriode()->getNomPeriode();

                }
                $k++;
            }

        }
        return $tabNAV;
    }
    private function  noteRejet()
    {
        $em=$this->getDoctrine()->getManager();
        $cm=$em->getRepository(ClasseMatiereProfesseurAnnee::class)->findAll();
        $tabNAV=array();
        $k=0;
        for ($i=0;$i<sizeof($cm);$i++)
        {
            $typeNV=$em->getRepository(Note::class)->typeR($cm[$i]->getId());

            if($typeNV != null)
            {


                $tabNAV[$k]['annee']=$cm[$i]->getAnnee()->getDateDebut()->format("d-m-Y H:i:s").'-'.$cm[$i]->getAnnee()->getDateFin()->format("d-m-Y H:i:s");
                $tabNAV[$k]['professeur']=$cm[$i]->getProfesseur()->getNom().'-'.$cm[$i]->getProfesseur()->getPrenom();
                $tabNAV[$k]['classe']=$cm[$i]->getTypeClasse()->getClasse()->getLibelle().' '.$cm[$i]->getTypeClasse()->getLibelle();
                $tabNAV[$k]['matiere']=$cm[$i]->getClasseMatiere()->getMatiere()->getLibelle();
                $tabNAV[$k]['coefficient']=$cm[$i]->getClasseMatiere()->getCoefficient();
                $tabNAV[$k]['effectif']=sizeof($em->getRepository(EleveTypeClasse::class)->findBy(array('type_classe'=>$cm[$i]->getTypeClasse()->getId())));
                $tabNAV[$k]['idCm']=$cm[$i]->getId();

                for ($j=0;$j<sizeof($typeNV);$j++)
                {
                    $note=$em->getRepository(Note::class)->findBy(array('classe_matiere_professeur_annee'=>$cm[$i]->getId(),'type'=>$typeNV[$j]['type']));

                    for ($l=0;$l<sizeof($note);$l++)
                    {
                        $tabNAV[$k]['note'][$j][$l]['valeur']=$note[$l]->getNote();
                        $tabNAV[$k]['note'][$j][$l]['type']=$note[$l]->getType();
                        $el=$em->getRepository(Eleve::class)->find($note[$l]->getEleve());
                        $tabNAV[$k]['note'][$j][$l]['eleve']=$el->getPrenom().'  '.$el->getNom();
                    }
                    //    $tabNAV[$k]['notetype']['type']=$typeNV[$j]['type'];

                    // if($i==0)$tabNAV[$k]['periode']=$note[0]->getPeriode()->getNomPeriode();

                }
                $k++;
            }

        }
        return $tabNAV;
    }
    private function fiche($tc,$cm,$p)
    {
        $tci=$tc;
        $cmi=$cm;
        $pi=$p;

        $em=$this->getDoctrine()->getManager();
        $tc=$em->getRepository(TypeClasse::class)->find($tci);
        $cm=$em->getRepository(ClasseMatiere::class)->find($cmi);
        $p=$em->getRepository(Periode::class)->find($pi);
        $classprof=null;
        if(sizeof($em->getRepository(ClasseMatiereProfesseurAnnee::class)->findBy(array('type_classe'=>$tci,'classe_matiere'=>$cmi)))>0){
            $classprof=$em->getRepository(ClasseMatiereProfesseurAnnee::class)->findBy(array('type_classe'=>$tci,'classe_matiere'=>$cmi))[0];
            $prof=$em->getRepository(Professeur::class)->find($classprof->getProfesseur()->getId());

        }
        $eleve=$em->getRepository(EleveTypeClasse::class)->findBy(array("type_classe"=>$tci));

        $em=$this->getDoctrine()->getManager();
        $tab=array();
        for ($i=0; $i<sizeof($eleve);$i++)
        {
            $notes_interro=$em->getRepository(Note::class)->findBy(array("eleve"=>$eleve[$i]->getEleve()->getId(),'periode'=>$pi,'classe_matiere_professeur_annee'=>$classprof->getId(),'statut'=>"validé",'type'=>"interro"));
            $notes_devoir=$em->getRepository(Note::class)->findBy(array("eleve"=>$eleve[$i]->getEleve()->getId(),'periode'=>$pi,'classe_matiere_professeur_annee'=>$classprof->getId(),'statut'=>1,'type'=>"devoir"));
            if ($notes_interro==null)
            {
                $tab[$i]['interro1']=0;
                $tab[$i]['interro2']=0;
            }else{
                if (isset($notes_interro[0])) $tab[$i]['interro1']=$notes_interro[0]->getNote();
                if (isset($notes_interro[1]) )$tab[$i]['interro2']=$notes_interro[1]->getNote();
                if (!isset($notes_interro[1]) )$tab[$i]['interro2']=0;
            }
            $moyint=($tab[$i]['interro1']+$tab[$i]['interro2'])/2;
            $tab[$i]['MINT']=substr($moyint,0,4);;
            if ($notes_devoir==null)
            {
                $tab[$i]['devoir1']=0;
                $tab[$i]['devoir2']=0;
            }else{
                if (isset($notes_devoir[0])) $tab[$i]['devoir1']=$notes_devoir[0]->getNote();
                if (isset($notes_devoir[1]) )$tab[$i]['devoir2']=$notes_devoir[1]->getNote();
                if (!isset($notes_devoir[1]) )$tab[$i]['devoir2']=0;
            }
            $tab[$i]['eleve']=$eleve[$i]->getEleve()->getPrenom()."  ".$eleve[$i]->getEleve()->getNom();
            $moy=($moyint+$tab[$i]['devoir1']+$tab[$i]['devoir2'])/3;
            $tab[$i]['Moy']=substr($moy,0,4);
            $tab[$i]['MoyC']=substr( $moy*$cm->getCoefficient(),0,4);
        }

        return array('tab'=>$tab,
            'tc'=>$tc,
            'cm'=>$cm,
            'p'=>$p,
            'el'=>$eleve,
        );
    }

    /**
     * Lists all notes entities.
     *
     * @Route("/notes/validation/{idCm}/{type}", name="notes_validation")
     * @Method("GET")
     */
    public  function ValiderNoteAction($idCm,$type)
    {
        $em=$this->getDoctrine()->getManager();
        $notes =$em->getRepository(Note::class)->findBy(array('classe_matiere_professeur_annee'=>$idCm,'type'=>$type));

        for ($i=0; $i<sizeof($notes);$i++)
        {
            $actif=$em->getRepository(Note::class)->find($notes[$i]->getId());
            $actif->setStatut('validé');
            $em->persist($actif);
        }

        $em->flush();
       return $this->redirectToRoute('notes_index',array('id'=>1));
    }

    /**
     * Lists all notes entities.
     *
     * @Route("/notes/rejet/{idCm}/{type}", name="notes_rejet")
     * @Method("GET")
     */
    public  function RejetNoteAction($idCm,$type)
    {
        $em=$this->getDoctrine()->getManager();
        $notes =$em->getRepository(Note::class)->findBy(array('classe_matiere_professeur_annee_id'=>$idCm,'type'=>$type));

        for ($i=0; $i<sizeof($notes);$i++)
        {
            $actif=$em->getRepository(Note::class)->find($notes[$i]->getId());
            $actif->setStatut('non validé');
            $em->persist($actif);
        }

        $em->flush();
        return $this->redirectToRoute('notes_index',array('id'=>2));
    }

}
