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
        $em=$this->getDoctrine()->getManager();
        $ecole=$this->getUser()->getEcole();
        $annee=$em->getRepository(Annee::class)->findBy(array('ecole'=>$ecole->getId(),'cloture'=>0));
        $periode=$em->getRepository(Periode::class)->findBy(array('annee'=>$annee[0]->getId()));

        $classes=$em->getRepository(Classe::class)->findBy(array('ecole'=>$ecole->getId()));

        $tabClasse=$this->getClasseEcole($em,$classes);

        $param_type=1;
        if ($request->get('type') !="") $param_type=$request->get('type');

        $param_periode=1;
        if ($request->get('periode') !="") $param_periode=(int)$request->get('periode');

        $param_classe=0;
        if ($request->get('classe') !="")$param_classe=$request->get('classe');

        $tab= $this->getBulletin($param_type,$param_periode);
        return $this->render('AppBundle:Bulletin:index.html.twig', array(
            'tab'=>$tab,
            'param_type'=>$param_type,
            'param_periode'=>$param_periode,
            'tabClasse'=>$tabClasse,
            'param_classe'=>$param_classe,
            'periode'=>$periode

        ));
    }
    private function getClasseEcole($em,$classes)
    {
        $tabClasse=array();
        for ($i=0; $i<sizeof($classes);$i++)
        {
            $type=$em->getRepository(TypeClasse::class)->findBy(array('classe'=>$classes[$i]->getId()));
            for($j=0; $j<sizeof($type);$j++)
            {
                $tabClasse[$i][$j]['classe']=$classes[$i]->getLibelle()." ".$type[$j]->getLibelle();
                $tabClasse[$i][$j]['type']=$type[$j]->getId();
                $tabClasse[$i][$j]['number']=$i;
            }
        }

        return $tabClasse;
    }

    private function  getBulletin($param_type,$param_periode)
    {
        $em=$this->getDoctrine()->getManager();
        $tc=$em->getRepository(TypeClasse::class)->find($param_type);
        $p=$em->getRepository(Periode::class)->find($param_periode);
        $classprof=$em->getRepository(ClasseMatiereProfesseurAnnee::class)->findBy(array('type_classe'=>$tc));
        $eleve=$em->getRepository(EleveTypeClasse::class)->findBy(array("type_classe"=>$tc));
        $tab=array();
        for ($i=0; $i<sizeof($eleve);$i++)
        {
            $totalPoint=0;
            $totalCoeff=0;
            $moyTotal=0;

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

                    $actif=$em->getRepository(Note::class)->findBy(array("eleve"=>$eleve[$i]->getEleve()->getId(),'periode'=>$param_periode,'classe_matiere_professeur_annee'=>$classprof[$j]->getId(),'statut'=>"validé",'type'=>$allInterro[$n]));
                    if ($actif!= null){
                        $tab[$i]['interro_name'][$n]['nom']=$actif[0]->getType();
                        $tab[$i]['interro_name'][$n]['etat']=$actif[0]->getEtat();
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

                    $actif=$em->getRepository(Note::class)->findBy(array("eleve"=>$eleve[$i]->getEleve()->getId(),'periode'=>$param_periode,'classe_matiere_professeur_annee'=>$classprof[$j]->getId(),'statut'=>"validé",'type'=>$allDevoir[$n]));
                    if ($actif!= null){
                        $tab[$i]['devoir_name'][$n]['nom']=$actif[0]->getType();
                        $tab[$i]['devoir_name'][$n]['etat']=$actif[0]->getEtat();
                        $tab[$i]['note'][$j]['devoir_note'][$n]['etat']=$actif[0]->getEtat();
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
                $totalPoint+=$moy*$classprof[$j]->getClasseMatiere()->getCoefficient();
                $totalCoeff+=$classprof[$j]->getClasseMatiere()->getCoefficient();
                $tab[$i]['note'][$j]['Moy']=substr($moy,0,4);
                $tab[$i]['note'][$j]['MoyC']=substr( $moy*$classprof[$j]->getClasseMatiere()->getCoefficient(),0,4);
                $tab[$i]['note'][$j]['coeffiecient']=$classprof[$j]->getClasseMatiere()->getCoefficient();
                $tab[$i]['note'][$j]['matiere']=$classprof[$j]->getClasseMatiere()->getMatiere()->getLibelle();
            }
            $tab[$i]['totalPoint']=$totalPoint;
            $tab[$i]['totalCoeff']=$totalCoeff;
            if($totalCoeff != 0) $moyTotal=$totalPoint/$totalCoeff;
            $tab[$i]['moyTotal']=$moyTotal;

        }
        return array('tab'=>$tab,'tc'=>$tc,'el'=>$eleve,'p'=>$p);
    }
}
