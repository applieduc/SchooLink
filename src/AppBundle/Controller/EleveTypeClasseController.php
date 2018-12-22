<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Annee;
use AppBundle\Entity\Censeur;
use AppBundle\Entity\Classe;
use AppBundle\Entity\Eleve;
use AppBundle\Entity\EleveTypeClasse;
use AppBundle\Entity\TypeClasse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Eleve controller.
 *
 * @Route("eleve_classe")
 */
class EleveTypeClasseController extends Controller
{
    /**
     * Lists all eleve entities.
     *
     * @Route("/{classe}", name="eleve_classe_index")
     * @Method("GET")
     */
    public function indexAction(Request $request,$classe)
    {
        $em = $this->getDoctrine()->getManager();
        $ecole=$em->getRepository(Censeur::class)->find($this->getUser()->getId())->getEcole();
        $annee=$em->getRepository(Annee::class)->findOneBy(array('ecole'=>$ecole->getId(),'cloture'=>0));

        if($annee==null){
            $this->get('session')->getFlashBag()->set('info','Vous n\'avez aucune année académique en cours');
            return $this->redirectToRoute('classe_index');

         }  
        $eleves=$this->get_eleve($classe);
        $classes_type=$this->get_classes_type($classe);

         
        
        return $this->render('AppBundle:EleveClasse:index.html.twig', array(
            'eleves' => $eleves,
            'classe'=>$classe,
            'classes_type'=>$classes_type
          
        ));
    }

    /**
     * Lists all eleve entities.
     *
     * @Route("/classe_type/{type}", name="show_type_edit")
     * @Method("GET")
     */
    public function show_editAction(TypeClasse $type)
    {

        return $this->render('AppBundle:EleveClasse:edit.html.twig', array(
            'type' => $type,


        ));
    }
    /**
     * Lists all eleve entities.
     *
     * @Route("/classe_type_edit/{type}", name="type_edit")
     * @Method("GET")
     */
    public function type_editAction(Request $request,TypeClasse $type)
    {
        $em = $this->getDoctrine()->getManager();
        $type->setLibelle($request->get('classe'));
        $em->persist($type);
        $em->flush();
        return $this->redirectToRoute('eleve_classe_index',array("classe"=>$type->getClasse()->getId()));

    }

    private function get_classes_type($classe){
        $annee=$this->get('session')->get('annee');
        $em = $this->getDoctrine()->getManager();
        $classes_type=$em->getRepository(TypeClasse::class)->findBy(array('archiver'=>false,'classe'=>$classe));
        $data=array();
        $i=0;

        foreach ($classes_type as $val) {
            if($val->getDateCreation() >$annee->getDateDebut() &&  $val->getDateCreation()<$annee->getDateFin() ){
                $eleves=$em->getRepository(EleveTypeClasse::class)->findBy(array('type_classe'=>$val->getId(),'archiver'=>false));
                $data[$i]['type_classe']=$val;
                $data[$i]['eff']=sizeof($eleves);
                $i++;
            }


        }
        return $data;
    }
    private function get_eleve($classe){
        $em = $this->getDoctrine()->getManager();
        $eleves = $em->getRepository('AppBundle:Eleve')->findBy(array('archiver' =>false,'classe'=>$classe));
        $ecole=$em->getRepository(Censeur::class)->find($this->getUser()->getId())->getEcole();

        $annee=$em->getRepository(Annee::class)->findOneBy(array('ecole'=>$ecole->getId(),'cloture'=>0));

        $eleves_not_classe=array();
        $i=0;
        foreach ($eleves as $val) {
            $eleve = $em->getRepository('AppBundle:EleveTypeClasse')->findOneBy(array('eleve' =>$val->getId(),'archiver'=>false));
            if($eleve!=null ){
                if ($eleve->getDateCreation() < $annee->getDateDebut()){
                    $eleves_not_classe[$i]=$val;
                    $i++;
                }


            }elseif ($eleve==null){
                $eleves_not_classe[$i]=$val;
                $i++;
            }

           
        }

        return $eleves_not_classe;
    }
     /**
     * Lists all eleve entities.
     *
     * @Route("/get_eleves", name="get_eleves")
     * @Method("GET")
     */
    public function get_eleves(){
        return new Response(json_encode($this->get_eleve()));
    }

    /**
     * Lists all eleve entities.
     *
     * @Route("/save_type_classe_eleve/{classe}", name="save_type_classe_eleve")
     * @Method("GET")
     */

    public function save_type_classe_eleve(Request $request,Classe $classe)
    {

        $em = $this->getDoctrine()->getManager();
        $classe_type=$em->getRepository(TypeClasse::class)->findOneBy(array('libelle'=>$request->get('type'),'classe'=>$classe->getId()));

        if ($classe_type==null){
            $classe_type=new TypeClasse();
            $classe_type->setClasse($classe);
            $classe_type->setLibelle($request->get('type'));
            $em->persist($classe_type);
            $em->flush();
        }

        $session=$this->get("session");
        $ecole=$em->getRepository(Censeur::class)->find($this->getUser()->getId())->getEcole();
        $annee=$em->getRepository(Annee::class)->findOneBy(array('ecole'=>$ecole->getId(),'cloture'=>0));

        $classe_typ_selct='';
       $classe_typ=$em->getRepository(TypeClasse::class)->findBy(array('libelle'=>$request->get('type'),'classe'=>$classe->getId()));
       foreach ($classe_typ as $item){

           if($item->getDateCreation() > $annee->getDateDebut() &&  $item->getDateCreation()<$annee->getDateFin() ){
               $classe_typ_selct=$item;
           }

           }
        $em->flush();
        for ($i=1;$i<=(int)$request->get('nb_eleves');$i++){
            $id_eleve=(int)$request->get('eleve'.$i);
            if($id_eleve!=0){
                $eleve=  $em->getRepository(Eleve::class)->find($id_eleve);
                if ($em->getRepository(EleveTypeClasse::class)->findOneBy(array('type_classe'=>$classe_typ_selct->getId(),'eleve'=>$eleve))==null){
                    $elves_classe_type=new EleveTypeClasse();
                    $elves_classe_type->setTypeClasse($classe_typ_selct);
                    $elves_classe_type->setEleve($eleve);
                    $em->persist($elves_classe_type);
                    $em->flush();
                }
            }
          

        }
        return $this->redirectToRoute('eleve_classe_index',array('classe'=>$classe->getId()));

    }
}