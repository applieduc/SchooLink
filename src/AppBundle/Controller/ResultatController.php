<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ClasseMatiere;
use AppBundle\Entity\ClasseMatiereProfesseurAnnee;
use AppBundle\Entity\Eleve;
use AppBundle\Entity\EleveTypeClasse;
use AppBundle\Entity\Note;
use AppBundle\Entity\Periode;
use AppBundle\Entity\Professeur;
use AppBundle\Entity\TypeClasse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Eleve controller.
 *
 * @Route("resultat")
 */
class ResultatController extends Controller
{
    /**
     * Lists all eleve entities.
     *
     * @Route("/", name="resultat_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $notes=$em->getRepository(Note::class)->findAll();
        $tc=$em->getRepository(TypeClasse::class)->findAll();
        $cm=$em->getRepository(ClasseMatiere::class)->findAll();
       $periode=$em->getRepository(Periode::class)->findAll();
        return $this->render('AppBundle:Resultat:index.html.twig', array(
            'notes'=>$notes,
            'tc'=>$tc,
            'cm'=>$cm,
            'periode'=>$periode

            ));
    }


    /**
     * Lists all eleve entities.
     *
     * @Route("/fiche_note", name="fiche_note")
     * @Method("GET")
     */
    public function ficheAction(Request $request)
    {
        $tci=$request->get('tc');
        $cmi=$request->get('cm');
        $pi=$request->get('p');

        $em=$this->getDoctrine()->getManager();
        $tc=$em->getRepository(TypeClasse::class)->find($tci);
        $cm=$em->getRepository(ClasseMatiere::class)->find($cmi);
        $p=$em->getRepository(Periode::class)->find($pi);
        $classprof=$em->getRepository(ClasseMatiereProfesseurAnnee::class)->findBy(array('type_classe'=>$tci,'classe_matiere'=>$cmi))[0];
        $prof=$em->getRepository(Professeur::class)->find($classprof->getProfesseur()->getId());
        $eleve=$em->getRepository(EleveTypeClasse::class)->findBy(array("type_classe"=>$tci));

        $em=$this->getDoctrine()->getManager();
            $tab=array();
            for ($i=0; $i<sizeof($eleve);$i++)
            {
                $notes_interro=$em->getRepository(Note::class)->findBy(array("eleve"=>$eleve[$i]->getEleve()->getId(),'periode'=>$pi,'classe_matiere_professeur_annee'=>$classprof->getId(),'statut'=>1,'type'=>"interro"));
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

        return $this->render('AppBundle:Resultat:fiche.html.twig', array('tab'=>$tab,
            'tc'=>$tc,
            'cm'=>$cm,
            'p'=>$p,
            'el'=>$eleve,
        ));
    }

    /**
     * Lists all eleve entities.
     *
     * @Route("/bulletiin", name="bulletin")
     * @Method("GET")
     */
    public function bulletiinAction(Request $request)
    {
        $tci=$request->get('tc');
        $pi=$request->get('p');

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
                $notes_interro=$em->getRepository(Note::class)->findBy(array("eleve"=>$eleve[$i]->getEleve()->getId(),'periode'=>$pi,'classe_matiere_professeur_annee'=>$classprof[$j]->getId(),'statut'=>1,'type'=>"interro"));
                $notes_devoir=$em->getRepository(Note::class)->findBy(array("eleve"=>$eleve[$i]->getEleve()->getId(),'periode'=>$pi,'classe_matiere_professeur_annee'=>$classprof[$j]->getId(),'statut'=>1,'type'=>"devoir"));

                if ($notes_interro==null)
                {
                    $tab[$i]['note'][$j]['interro1']=0;
                    $tab[$i]['note'][$j]['interro2']=0;
                }else{
                    if (isset($notes_interro[0])) $tab[$i]['note'][$j]['interro1']=$notes_interro[0]->getNote();
                    if (isset($notes_interro[1]) )$tab[$i]['note'][$j]['interro2']=$notes_interro[1]->getNote();
                    if (!isset($notes_interro[1]) )$tab[$i]['note'][$j]['interro2']=0;
                }
                $moyint=($tab[$i]['note'][$j]['interro1']+$tab[$i]['note'][$j]['interro2'])/2;
                $tab[$i]['note'][$j]['MINT']=substr($moyint,0,4);

              //  $tab[$i][$j]['prof']=$prof->getPrenom()." ".$prof->getNom();

                if ($notes_devoir==null)
                {
                    $tab[$i]['note'][$j]['devoir1']=0;
                    $tab[$i]['note'][$j]['devoir2']=0;
                }else{
                    if (isset($notes_devoir[0])) $tab[$i]['note'][$j]['devoir1']=$notes_devoir[0]->getNote();
                    if (isset($notes_devoir[1]) )$tab[$i]['note'][$j]['devoir2']=$notes_devoir[1]->getNote();
                    if (!isset($notes_devoir[1]) )$tab[$i]['note'][$j]['devoir2']=0;
                }
                $tab[$i]['note'][$j]['matiere']=$classprof[$j]->getClasseMatiere()->getMatiere()->getLibelle();
                $moy=($moyint+$tab[$i]['note'][$j]['devoir1']+$tab[$i]['note'][$j]['devoir2'])/3;
                $tab[$i]['note'][$j]['Moy']=substr($moy,0,4);
                $tab[$i]['note'][$j]['MoyC']=substr( $moy*$classprof[$j]->getClasseMatiere()->getCoefficient(),0,4);

                $tab[$i]['note'][$j]['coeffiecient']=$classprof[$j]->getClasseMatiere()->getCoefficient();
            }

        }

        return $this->render('AppBundle:Resultat:bulletin.html.twig', array('tab'=>$tab,
            'tc'=>$tc,
            'p'=>$p,
            'el'=>$eleve,
        ));
    }
private function filter()
{

}
}
