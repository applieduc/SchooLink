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
        $ecole=$this->getUser()->getEcole();
        $annee=$em->getRepository(Annee::class)->findBy(array('ecole'=>$ecole->getId(),'cloture'=>0));
        $periode=$em->getRepository(Periode::class)->findBy(array('annee'=>$annee[0]->getId()));

        $classes=$em->getRepository(Classe::class)->findBy(array('ecole'=>$ecole->getId()));

        $tabClasse=$this->getClasseEcole($em,$classes);

        $param_classe=0;
        if ($request->get('classe') !="")$param_classe=$request->get('classe');

        $matieres=$em->getRepository(ClasseMatiere::class)->findBy(array('classe'=>$classes[$param_classe]->getId()));


        $param_type=$tabClasse[0][0]['type'];
        if ($request->get('type') !=""){

            $param_type=$request->get('type');
        }

        $param_matiere=0;
        if ($request->get('matiere') !="")$param_matiere=$request->get('matiere');
        $param1_matiere=$matieres[$param_matiere]->getId();

        $param_periode=$periode[0]->getId();
        if ($request->get('periode') !="") $param_periode=(int)$request->get('periode');

        if ($request->get('cmpa')!="") $this->removeCompo($request->get('cmpa'),$request->get('type2'));

        $fiche=$this->fiche((int)$param_type,$param1_matiere,$param_periode);


        $notes=$em->getRepository(Note::class)->findAll();
        $tc=$em->getRepository(Classe::class)->findBy(array('ecole'=>$ecole->getId()));
        $t=array();
        $i=0;
        foreach ($tc as $c){
            $t[$i]=$em->getRepository(TypeClasse::class)->findOneBy(array('classe'=>$c->getId()));
            $i++;
        }

        $cm=$em->getRepository(ClasseMatiere::class)->findAll();

        return $this->render('AppBundle:Resultat:index.html.twig', array(
            'matiere'=>$matieres,
            'tabClasse'=>$tabClasse,
            'param_type'=>$param_type,
            'param_classe'=>$param_classe,
            'param_matiere'=>$param_matiere,
            'param_periode'=>$param_periode,
            'annee'=>$annee,
            'classe'=>$classes[0]->getLibelle(),
            'periode'=>$periode,
            'tab'=>$fiche

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
    /**
     * Lists all eleve entities.
     *
     * @Route("/filter/{typeId}/{matiereId}/{periodeId}/{classeId}", name="resultat_index_filtre")
     * @Method("GET")
     */
    public function indexFiltreAction($typeId,$matiereId,$periodeId,$classeId)
    {

        $em=$this->getDoctrine()->getManager();
        $classes=$em->getRepository(Classe::class)->findAll();
        $ecole=$em->getRepository(Censeur::class)->find($this->getUser()->getId())->getEcole();
          

        $annee=$em->getRepository(Annee::class)->findOneBy(array('ecole'=>$ecole->getId(),'cloture'=>0));
       
        $classes=$em->getRepository(Classe::class)->findBy(array('ecole'=>$ecole->getId()));
        $tabClasse=array();

        for ($i=0; $i<sizeof($classes);$i++)
        {
            $type=$em->getRepository(TypeClasse::class)->findBy(array('classe'=>$classes[$i]->getId()));
            for($j=0; $j<sizeof($type);$j++)
            {
                $tabClasse[$i][$j]['classe']=$classes[$i]->getLibelle()." ".$type[$j]->getLibelle();
                $tabClasse[$i][$j]['type']=$type[$j]->getId();
            }
        }
        $matiere=$em->getRepository(ClasseMatiere::class)->findBy(array('classe'=>$classeId));

        $ecole=$em->getRepository(Censeur::class)->find($this->getUser()->getId())->getEcole();


        $annee=$em->getRepository(Annee::class)->findOneBy(array('ecole'=>$ecole->getId(),'cloture'=>0));

        $id=0;
        if ($annee!=null){
            $id=$annee->getId();
        }
        $notes=$em->getRepository(Note::class)->findAll();
        $tc=$em->getRepository(Classe::class)->findBy(array('ecole'=>$ecole->getId()));
        $t=array();
        $i=0;
        foreach ($tc as $c){
            $t[$i]=$em->getRepository(TypeClasse::class)->findOneBy(array('classe'=>$c->getId()));
            $i++;
        }
        if(sizeof($t)==0){
            $this->get('session')->getFlashBag()->set('success','Vous n\'avez aucune sous classe');
            return $this->redirectToRoute('classe_index');
        }

        $cm=$em->getRepository(ClasseMatiere::class)->findAll();
        $periode=$em->getRepository(Periode::class)->findBy(array('annee'=>0));
        return $this->render('AppBundle:Resultat:index.html.twig', array(
            'matiere'=>$matiere,
            'tabClasse'=>$tabClasse,
            'notes'=>$notes,
            'tc'=>$t,
            'annee'=>$annee,
            'periode'=>$periode,
            'tab'=>$this->fiche($typeId,$matiereId,$periodeId)

        ));
    }


    /**
     * Lists all eleve entities.
     *
     * @Route("/get_matiere_with_typeClasse", name="get_matiere_with_typeClasse")
     * @Method("POST")
     */
    public function getMatiereClasseAction(Request $request){
        $em=$this->getDoctrine()->getManager();

        $type=$em->getRepository(TypeClasse::class)->find((int)$request->get('type'));
        $mat=$em->getRepository(ClasseMatiere::class)->findBy(array('classe'=>$type->getClasse()->getId()));
         $matieres=array();
         $i=0;
         foreach ($mat as $value){
             $matieres[$i]['lib']=$value->getMatiere()->getLibelle();
             $matieres[$i]['id']=$value->getMatiere()->getId();
             $i++;
         }
        return new Response(json_encode(array('mats'=>$matieres)));
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
        $classprof=null;
        if(sizeof($em->getRepository(ClasseMatiereProfesseurAnnee::class)->findBy(array('type_classe'=>$tci,'classe_matiere'=>$cmi)))>0){
            $classprof=$em->getRepository(ClasseMatiereProfesseurAnnee::class)->findBy(array('type_classe'=>$tci,'classe_matiere'=>$cmi))[0];
            $prof=$em->getRepository(Professeur::class)->find($classprof->getProfesseur()->getId());

        }
        $eleve=$em->getRepository(EleveTypeClasse::class)->findBy(array("type_classe"=>$tci));

        $em=$this->getDoctrine()->getManager();
            $tab=array();
            $allInterro=$em->getRepository(Note::class)->typeInterro($classprof->getId());
            $allDevoir=$em->getRepository(Note::class)->typeDevoir($classprof->getId());
            for ($i=0; $i<sizeof($eleve);$i++)
            {
                $totalInterro=0;
                $totalDevoir=0;
                for ($n=0; $n<sizeof($allInterro);$n++)
                {
                    $actif=$em->getRepository(Note::class)->findBy(array("eleve"=>$eleve[$i]->getEleve()->getId(),'periode'=>$pi,'classe_matiere_professeur_annee'=>$classprof->getId(),'statut'=>1,'type'=>$allInterro[$n]))[0]->getNote();

                    $tab[$i][$allInterro[$n]]=$actif;
                    $totalInterro+=$actif;
                }
                for ($n=0; $n<sizeof($allDevoir);$n++)
                {
                    $actif=$em->getRepository(Note::class)->findBy(array("eleve"=>$eleve[$i]->getEleve()->getId(),'periode'=>$pi,'classe_matiere_professeur_annee'=>$classprof->getId(),'statut'=>1,'type'=>$allInterro[$n]))[0]->getNote();
                    $tab[$i][$allDevoir[$n]]=$actif;
                    $totalDevoir+=$actif;
                }

             if ($totalInterro != 0)
             {
                 $moyint=($totalInterro)/sizeof($allInterro);
             }else{
                 $moyint=0.00000000;
             }

                $tab[$i]['MINT']=substr($moyint,0,4);;
                $tab[$i]['eleve']=$eleve[$i]->getEleve()->getPrenom()."  ".$eleve[$i]->getEleve()->getNom();
                if ($totalDevoir !=0)
                {
                    $moy=($moyint+$totalDevoir)/(sizeof($allDevoir)+1);
                }else{
                    $moy=0.00000000;
                }
                $moy=($moyint+$totalDevoir)/(sizeof($allDevoir)+1);
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

    private function removeCompo($cmpa,$type)
    {
        $em=$this->getDoctrine()->getManager();
        $notes=$em->getRepository(Note::class)->findBy(array('classe_matiere_professeur_annee'=>$cmpa,'type'=>$type));

        for ($i=0; $i<sizeof($notes); $i++)
        {
            if ($notes[$i]->getEtat() == 1)
            {
                $notes[$i]->setEtat(false);
            }else{
                $notes[$i]->setEtat(true);
            }
         $em->persist(  $notes[$i]);
        }
        $em->flush();
    }
    private function fiche($tc,$cm,$p)
    {
        $tci=$tc;
        $cmi=$cm;
        $pi=$p;
        $tab=array();

        $em=$this->getDoctrine()->getManager();
        $tc=$em->getRepository(TypeClasse::class)->find($tci);
        $cm=$em->getRepository(ClasseMatiere::class)->find($cmi);
        $p=$em->getRepository(Periode::class)->find($pi);
        $classprof=null;


        if(sizeof($em->getRepository(ClasseMatiereProfesseurAnnee::class)->findBy(array('type_classe'=>$tci,'classe_matiere'=>$cmi)))>0){
            $classprof=$em->getRepository(ClasseMatiereProfesseurAnnee::class)->findBy(array('type_classe'=>$tci,'classe_matiere'=>$cmi))[0];
            $prof=$em->getRepository(Professeur::class)->find($classprof->getProfesseur()->getId());
            $eleve=$em->getRepository(EleveTypeClasse::class)->findBy(array("type_classe"=>$tci));

            $em=$this->getDoctrine()->getManager();

            $allInterro=$em->getRepository(Note::class)->typeInterro($classprof->getId());
            $allDevoir=$em->getRepository(Note::class)->typeDevoir($classprof->getId());
            for ($i=0; $i<sizeof($eleve);$i++)
            {
                $totalInterro=0;
                $totalDevoir=0;
                $diviseurInterro=0;
                $diviseurDevoir=0;
                for ($n=0; $n<sizeof($allInterro);$n++)
                {

                    $actif=$em->getRepository(Note::class)->findBy(array("eleve"=>$eleve[$i]->getEleve()->getId(),'periode'=>$pi,'classe_matiere_professeur_annee'=>$classprof->getId(),'statut'=>"validé",'type'=>$allInterro[$n]));
                    if ($actif!= null){
                        $tab[$i]['interro_name'][$n]['nom']=$actif[0]->getType();
                        $tab[$i]['interro_name'][$n]['etat']=$actif[0]->getEtat();
                        $tab[$i]['interro_note'][$n]=$actif[0]->getNote();
                        if($actif[0]->getEtat() == 1){
                            $totalInterro+=$actif[0]->getNote();
                            $diviseurInterro++;
                        }

                    }

                }
                for ($n=0; $n<sizeof($allDevoir);$n++)
                {

                    $actif=$em->getRepository(Note::class)->findBy(array("eleve"=>$eleve[$i]->getEleve()->getId(),'periode'=>$pi,'classe_matiere_professeur_annee'=>$classprof->getId(),'statut'=>"validé",'type'=>$allDevoir[$n]));
                    if ($actif!= null){
                        $tab[$i]['devoir_name'][$n]['nom']=$actif[0]->getType();
                        $tab[$i]['devoir_name'][$n]['etat']=$actif[0]->getEtat();
                        $tab[$i]['devoir_note'][$n]=$actif[0]->getNote();
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

                $tab[$i]['MINT']=substr($moyint,0,4);;
                $tab[$i]['eleve']=$eleve[$i]->getEleve()->getPrenom()."  ".$eleve[$i]->getEleve()->getNom();
                if ($totalDevoir !=0)
                {
                    $moy=($moyint+$totalDevoir)/(sizeof($allDevoir)+1);
                }else{
                    $moy=0.00000000;
                }
                $moy=($moyint+$totalDevoir)/(sizeof($diviseurDevoir)+1);
                $tab[$i]['Moy']=substr($moy,0,4);
                $tab[$i]['MoyC']=substr( $moy*$cm->getCoefficient(),0,4);
            }
            return array(
                'tab'=>$tab,
                'allInterro'=>$allInterro,
                'allDevoir'=>$allDevoir,
                'tc'=>$tc,
                'cm'=>$cm,
                'p'=>$p,
                'el'=>$eleve,
            );
        }


        return array(
            'tab'=>$tab
        );
    }

}
