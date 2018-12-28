<?php

namespace AppBundle\Controller;
use AppBundle\Entity\Censeur;

use AppBundle\Entity\Ecole;
use AppBundle\Entity\Annee;
use AppBundle\Entity\Eleve;
use AppBundle\Entity\Classe;
use AppBundle\Entity\Matiere;
use AppBundle\Entity\TypeClasse;
use AppBundle\Entity\Professeur;
use AppBundle\Entity\ClasseMatiere;
use AppBundle\Form\CenseurProfType;
use AppBundle\Form\EcoleType;
use AppBundle\Entity\EcoleProfesseur;
use AppBundle\Entity\EleveClasseEcoleAnnee;
use AppBundle\Entity\ClasseMatiereProfesseurAnnee;
use AppBundle\Entity\NotificationProfesseur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


/**
 * Gestion controller.
 *
 * @Route("programmation")
 */
class GestionController extends Controller
{



    /**
     * @Route("/", name="home_prog")
     */
    public function indexAction(Request $request)
    {
       
        $em = $this->getDoctrine()->getManager();

        $type_classe_data= array();
        $classes=$em->getRepository(Classe::class)->findBy(array('ecole'=>$this->getUser()->getEcole()->getId())) ;
        $prof_ecoles=$em->getRepository(EcoleProfesseur::class)->findBy(array('ecole'=>$this->getUser()->getEcole()->getId())) ;

        if($request->get('classe')){
            $type_classes=$em->getRepository(TypeClasse::class)->findBy(array('classe'=>(int)$request->get('classe'))) ;
           
            $i=0;
            foreach ($type_classes as $type_classe) {
               $type_classe_data[$i]['type_classe']=$type_classe;
             
               $type_classe_data[$i]['data']=$this->getData($type_classe);
             $i++;
            }
        }
      
        return $this->render('AppBundle:Gestion:index.html.twig',array('type_classes'=>$type_classe_data,'classes'=>$classes,'prof_ecoles'=>$prof_ecoles));
    }


     /**
     * @Route("/delte/{id}", name="del_prog")
     */
    public function deleteAction(Request $request,ClasseMatiereProfesseurAnnee $classeMatiereProfesseurAnnee)
    {
       
        $em = $this->getDoctrine()->getManager();
        $em->remove($classeMatiereProfesseurAnnee);
        $em->flush();
       
        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }
      /**
     * @Route("/get_info", name="get_info")
     */
    function getInfoAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $type_classes=$em->getRepository(TypeClasse::class)->findBy(array('classe'=>(int)$request->get('classe'))) ;
        $i=0;
        $data=array();
        foreach ($type_classes as $type_classe) {
           $data[$i]['libelle']=$type_classe->getLibelle();
           $data[$i]['id']=$type_classe->getId();
          $i++;
        }
        $classe_matieres=$em->getRepository(ClasseMatiere::class)->findBy(array('classe'=>(int)$request->get('classe'))) ;

        $j=0;
        $data1=array();
        foreach ($classe_matieres as $classe_matiere) {
           $data1[$j]['libelle']=$classe_matiere->getMatiere()->getLibelle();
           $data1[$j]['id']=$classe_matiere->getMatiere()->getId();
          $j++;
        }

        return new Response(json_encode(array("type_classes"=>$data,'classe_matieres'=>$data1)));
    }

    /**
     * @Route("/save_prog/", name="save_prog")
     * @Method({"POST"})
     */
    public function FlushAction(Request $request)
    {
       
        $em = $this->getDoctrine()->getManager();
        $ecole=$em->getRepository(Censeur::class)->find($this->getUser()->getId())->getEcole();
        $annee=$em->getRepository(Annee::class)->findOneBy(array('ecole'=>$ecole->getId(),'cloture'=>0));
        $type_classe=$em->getRepository(TypeClasse::class)->find((int)$request->get('type_classe')) ;
        $prof=$em->getRepository(Professeur::class)->find((int)$request->get('prof')) ;
        $classe_mat=$em->getRepository(ClasseMatiere::class)->findOneBy(array('classe'=>(int)$request->get('classe'),'matiere'=>(int)$request->get('matiere'))) ;

       $classeMatiereProfesseurAnnee=new ClasseMatiereProfesseurAnnee();
       $classeMatiereProfesseurAnnee->setTypeClasse($type_classe);
       $classeMatiereProfesseurAnnee->setProfesseur($prof);
       $classeMatiereProfesseurAnnee->setAnnee($annee);
       $classeMatiereProfesseurAnnee->setClasseMatiere($classe_mat);
       $em->persist($classeMatiereProfesseurAnnee);
        $em->flush();


        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
       
      
    }
    
    private function getData($type_classe)
    {
        $em = $this->getDoctrine()->getManager();
        $relate=$em->getRepository(ClasseMatiereProfesseurAnnee::class)->findBy(array('type_classe'=>$type_classe->getId()));
        $data=array();
        $i=0;
        foreach ($relate as $item) {
            $data[$i]['matiere']=$item->getClasseMatiere()->getMatiere();
            $data[$i]['professeur']=$item->getProfesseur();
            $data[$i]['id']=$item->getId();

            $i++;
        }
        return $data;
    }
    
}
