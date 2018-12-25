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
 * @Route("bulletin")
 */
class BulletinController extends Controller
{
    /**
     * Lists all eleve entities.
     *
     * @Route("/", name="bulletin_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $tci=1;
        $pi=1;

        $em=$this->getDoctrine()->getManager();
        $tc=$em->getRepository(TypeClasse::class)->find($tci);
        $p=$em->getRepository(Periode::class)->find($pi);
        $classprof=$em->getRepository(ClasseMatiereProfesseurAnnee::class)->findBy(array('type_classe'=>$tci));
        $eleve=$em->getRepository(EleveTypeClasse::class)->findBy(array("type_classe"=>$tci));




        $em=$this->getDoctrine()->getManager();
        $tab=array();
        for ($i=0; $i<sizeof($eleve);$i++)
        {
            $tab[$i]['eleve']=$eleve[$i]->getEleve()->getPrenom()."  ".$eleve[$i]->getEleve()->getNom();
            for ($j=0;$j<sizeof($classprof);$j++)
            {
                // $prof=$em->getRepository(Professeur::class)->find($classprof[$j]->getProfesseur()->getId());
                $allInterro=$em->getRepository(Note::class)->typeInterro($classprof[$j]->getId());
                $allDevoir=$em->getRepository(Note::class)->typeDevoir($classprof[$j]->getId());

                $totalInterro=0;
                $totalDevoir=0;
                $diviseurInterro=0;
                $diviseurDevoir=0;
                for ($n=0; $n<sizeof($allInterro);$n++)
                {

                    $actif=$em->getRepository(Note::class)->findBy(array("eleve"=>$eleve[$i]->getEleve()->getId(),'periode'=>$pi,'classe_matiere_professeur_annee'=>$classprof[$j]->getId(),'statut'=>"validé",'type'=>$allInterro[$n]));
                    if ($actif!= null){
                        $tab[$i]['note'][$j]['interro_name'][$n]['nom']=$actif[0]->getType();
                        $tab[$i]['note'][$j]['interro_name'][$n]['etat']=$actif[0]->getEtat();
                        $tab[$i]['note'][$j]['interro_note'][$n]['etat']=$actif[0]->getEtat();
                        $tab[$i]['note'][$j]['interro_note'][$n]['note']=$actif[0]->getNote();
                        if($actif[0]->getEtat() == 1){
                            $totalInterro+=$actif[0]->getNote();
                            $diviseurInterro++;
                        }

                    }

                }
                for ($n=0; $n<sizeof($allDevoir);$n++)
                {

                    $actif=$em->getRepository(Note::class)->findBy(array("eleve"=>$eleve[$i]->getEleve()->getId(),'periode'=>$pi,'classe_matiere_professeur_annee'=>$classprof[$j]->getId(),'statut'=>"validé",'type'=>$allDevoir[$n]));
                    if ($actif!= null){
                        $tab[$i]['note'][$j]['devoir_name'][$n]['nom']=$actif[0]->getType();
                        $tab[$i]['note'][$j]['devoir_name'][$n]['etat']=$actif[0]->getEtat();
                        $tab[$i]['note'][$j]['devoir_note'][$n['etat']]=$actif[0]->getEtat();
                        $tab[$i]['note'][$j]['devoir_note'][$n]['note']=$actif[0]->getNote();
                        if($actif[0]->getEtat() == 1)
                        {
                            $totalDevoir+=$actif[0]->getNote();
                            $diviseurDevoir ++;
                        }

                    }

                }
                if ($totalInterro != 0)
                {
                    $moyint=($totalInterro)/$diviseurInterro;
                }else{
                    $moyint=0.00000000;
                }

                $tab[$i]['note'][$j]['MINT']=substr($moyint,0,4);;
               // $tab[$i]['eleve']=$eleve[$i]->getEleve()->getPrenom()."  ".$eleve[$i]->getEleve()->getNom();
                if ($totalDevoir !=0)
                {
                    $moy=($moyint+$totalDevoir)/(sizeof($allDevoir)+1);
                }else{
                    $moy=0.00000000;
                }
                $moy=($moyint+$totalDevoir)/(sizeof($diviseurDevoir)+1);
                $tab[$i]['note'][$j]['Moy']=substr($moy,0,4);
                $tab[$i]['note'][$j]['MoyC']=substr( $moy*$classprof[$j]->getClasseMatiere()->getCoefficient(),0,4);
                $tab[$i]['note'][$j]['coeffiecient']=$classprof[$j]->getClasseMatiere()->getCoefficient();
                $tab[$i]['note'][$j]['matiere']=$classprof[$j]->getClasseMatiere()->getMatiere()->getLibelle();
            }

        }

        return $this->render('AppBundle:Bulletin:index.html.twig', array('tab'=>$tab,
            'tc'=>$tc,
            'p'=>$p,
            'el'=>$eleve,
        ));
    }

}
