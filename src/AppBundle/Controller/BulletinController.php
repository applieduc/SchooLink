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
use AppBundle\Entity\Resultat;
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

        $tab= $this->getBulletin($param_type,$param_periode,$annee);
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

    private function  getBulletin($param_type,$param_periode,$annee)
    {
        $em=$this->getDoctrine()->getManager();
        $tc=$em->getRepository(TypeClasse::class)->find($param_type);
        $p=$em->getRepository(Periode::class)->find($param_periode);
        $classprof=$em->getRepository(ClasseMatiereProfesseurAnnee::class)->findBy(array('type_classe'=>$tc));
        $eleve=$em->getRepository(EleveTypeClasse::class)->findBy(array("type_classe"=>$tc));
        $tab=array();
        $tab2=array();
        for ($i=0; $i<sizeof($eleve);$i++)
        {
            $totalPoint=0;
            $totalCoeff=0;
            $moyTotal=0;

            $tab[$i]['eleve']=$eleve[$i]->getEleve()->getPrenom()."  ".$eleve[$i]->getEleve()->getNom();
            $tab2[$i]['eleve']=$eleve[$i]->getEleve()->getPrenom()."  ".$eleve[$i]->getEleve()->getNom();
            for ($j=0;$j<sizeof($classprof);$j++)
            {
                // $prof=$em->getRepository(Professeur::class)->find($classprof[$j]->getProfesseur()->getId());
                $allInterro=$em->getRepository(Note::class)->typeInterro($classprof[$j]->getId(),$param_periode);
                $allDevoir=$em->getRepository(Note::class)->typeDevoir($classprof[$j]->getId(),$param_periode);

                $totalInterro=0;
                $totalDevoir=0;
                $diviseurInterro=0;
                $diviseurDevoir=0;
                for ($n=0; $n<sizeof($allInterro);$n++)
                {

                    $datetimer=substr($allInterro[$n]['dateCreation'],0,13);

                    $actif=$em->getRepository(Note::class)->getNote($eleve[$i]->getEleve()->getId(),$param_periode,$classprof[$j]->getId(),$datetimer);

                    if ($actif!= null){
                        $tab[$i]['interro_name'][$n]['nom']=$actif['type'];
                        $tab[$i]['interro_name'][$n]['etat']=$actif['etat'];
                        $tab[$i]['interro_name'][$n]['date']=$datetimer;
                        $tab[$i]['interro_note'][$n]=$actif['note'];
                        if($actif['etat'] == 1){
                            $totalInterro+=$actif['note'];
                            $diviseurInterro++;
                        }

                    }else{
                        $tab[$i]['interro_name'][$n]['nom']=$allInterro[$n]['type'];
                        $tab[$i]['interro_name'][$n]['etat']=$allInterro[$n]['etat'];
                        $tab[$i]['interro_note'][$n]=0;
                    }

                }
                for ($n=0; $n<sizeof($allDevoir);$n++)
                {


                    $datetimer=substr($allDevoir[$n]['dateCreation'],0,13);
                    $actif=$em->getRepository(Note::class)->getNote($eleve[$i]->getEleve()->getId(),$param_periode,$classprof[$j]->getId(),$datetimer);
                    if ($actif!= null){
                        $tab[$i]['devoir_name'][$n]['nom']=$actif['type'];
                        $tab[$i]['devoir_name'][$n]['etat']=$actif['etat'];
                        $tab[$i]['devoir_name'][$n]['date']=$datetimer;
                        $tab[$i]['devoir_note'][$n]=$actif['note'];
                        if($actif['etat'] == 1)
                        {
                            $totalDevoir+=$actif['note'];
                            $diviseurDevoir ++;
                        }

                    }else{
                        $tab[$i]['devoir_name'][$n]['nom']=$allInterro[$n]['type'];
                        $tab[$i]['devoir_name'][$n]['etat']=$allInterro[$n]['etat'];
                        $tab[$i]['devoir_note'][$n]=0;
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
                $tab[$i]['note'][$j]['obs']=$this->getObservation(substr($moy,0,4));
                $tab[$i]['note'][$j]['MoyC']=substr( $moy*$classprof[$j]->getClasseMatiere()->getCoefficient(),0,4);
                $tab[$i]['note'][$j]['coeffiecient']=$classprof[$j]->getClasseMatiere()->getCoefficient();
                $tab[$i]['note'][$j]['matiere']=$classprof[$j]->getClasseMatiere()->getMatiere()->getLibelle();
            }
            $tab[$i]['totalPoint']=$totalPoint;
            $tab[$i]['totalCoeff']=$totalCoeff;
            if($totalCoeff != 0) $moyTotal=$totalPoint/$totalCoeff;
            $tab[$i]['moyTotal']=$moyTotal;
            $tab2[$i]['moyTotal']=$moyTotal;
         /*   $resultat=$em->getRepository(Resultat::class)->findBy(array('eleve'=>$eleve[$i]->getEleve()->getId(),'type_classe'=>$param_type,'annee'=>$annee[0]->getId()));
            if ($param_periode==sizeof($p)){
                $tab[$i]['moyAn']=$resultat[0]->getMoyAnnuelle();
                $tab[$i]['rangAn']=$resultat[0]->getRangAnnuelle();
                $tab[$i]['obs']=$resultat[0]->getObs();
                if ($param_periode=2){
                    $tab[$i]['moyGen1']=$resultat[0]->getMoyGen1();
                    $tab[$i]['rang1']=$resultat[0]->getRang1();
                }
                if ($param_periode=3){
                    $tab[$i]['moyGen2']=$resultat[0]->getMoyGen2();
                    $tab[$i]['rang2']=$resultat[0]->getRang2();
                }
            }

            if($resultat!=null)
            {
                if($param_periode==1){
                    $resultat->setMoyGen1($moyTotal);
                    $resultat->setEleve($eleve[$i]->getEleve());
                    $resultat->setClasseMatierePrfesseurAnnee($classprof);
                    $resultat->setCoeff($totalCoeff);
                    }
                if($param_periode==2){
                    $resultat->setMoyGen2($moyTotal);
                    if (sizeof($p==2))
                    {
                        $moyAnn=($resultat->getMoyGen1()+$moyTotal)/2;
                        $obs="passe en classe supérieure";
                        $resultat->setMoyAnnuelle($moyAnn);
                        if($moyAnn<10)$obs="Redouble la classe";
                        $resultat->setObs($obs);

                    }
                }
                if($param_periode==3){
                    $resultat->setMoyGen3($moyTotal);
                    if (sizeof($p==3))
                    {
                        $moyAnn=($resultat->getMoyGen1()+$resultat->getMoyGen2()+$moyTotal)/3;
                        $obs="passe en classe supérieure";
                        $resultat->setMoyAnnuelle($moyAnn);
                        if($moyAnn<10)$obs="Redouble la classe";
                        $resultat->setObs($obs);
                    }
                }
            }
            else{
                $resultat=new Resultat();
                $resultat->setMoyGen1($moyTotal);
                $resultat->setEleve($eleve[$i]->getEleve());
                $resultat->setAnnee($annee);
                $resultat->setTypeClasse($tc);
                $resultat->setCoeff($totalCoeff);
            }
            $em->persist($resultat);
            $em->flush();*/
        }
        return array('tab'=>$tab,'tab2'=>$this->tri($tab2),'tc'=>$tc,'el'=>$eleve,'p'=>$p);
    }
    private   function  getObservation($note){
        $etat="";
        $note=explode(".",$note)[0];
        if ($note==0)
        {
            $etat="Nul";
        }elseif ($note>0 and $note<4)
        {
            $etat="Mal";
        }elseif ($note>=4 and $note<8)
        {
            $etat="médiocre";
        }elseif ($note>=8 and $note<12)
        {
            $etat="Passable";
        }
        elseif ($note>=12 and $note<14)
        {
            $etat="assez bien";

        }elseif ($note>=14 and $note<16)
        {
            $etat="Bien";
        }elseif ($note>=16 and $note<20)
        {
            $etat="Très bien";
        }elseif ($note == 20)
        {$etat="Excellent";

        }
        return $etat;
    }

    private function tri($table)
    {
        for ($i=0; $i<sizeof($table);$i++)
        {
            for ($j=1; $j<sizeof($table);$j++)
            {
                if ($table[$i]['moyTotal']>$table[$j]['moyTotal'])
                {
                    $int=$table[$i];
                    $table[$i]['moyTotal']=$table[$j]['moyTotal'];
                    $table[$j]['moyTotal']=$int;
                }
            }
        }
        return $table;
    }
}
