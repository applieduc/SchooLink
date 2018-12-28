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
    public function indexAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $ecole=$em->getRepository(Censeur::class)->find($this->getUser()->getId())->getEcole();

       $annee=$em->getRepository(Annee::class)->findOneBy(array('ecole'=>$ecole->getId(),'cloture'=>0));
        //$annee=$request->get('session')->get('annee');
       
        if($annee==null){
            $this->get('session')->getFlashBag()->set('info','Créer une nouvelle année académique');
            return $this->redirectToRoute('new_school',array('id'=>0));

         }  
        $cm=$em->getRepository(ClasseMatiereProfesseurAnnee::class)->findBy(array('annee'=>$annee->getId()));
        
        if(sizeof($cm)==0){
               $this->get('session')->getFlashBag()->set('success','Vous n\'avez aucune matiere attribuer');
                return $this->redirectToRoute('classe_index');
    
        }
        if ($id==1)
        {
            return $this->render('AppBundle:Notes:index.html.twig', array(

                'noteNV'=>$this->noteNonValide($cm)
            ));

        }else{
            return $this->render('AppBundle:Notes:index.html.twig', array(
                'noteR'=>$this->noteRejet   ($cm)
            ));
        }

    }

    private function  noteNonValide($cm)
    {
        $em=$this->getDoctrine()->getManager();
        $tabNAV=array();
        $k=0;
        for ($i=0;$i<sizeof($cm);$i++)
        {
            $typeNV=$em->getRepository(Note::class)->typeNV($cm[$i]->getId());

            if($typeNV != null)
            {



                for ($j=0;$j<sizeof($typeNV);$j++)
                {
                    $tabNAV[$k]['annee']=$cm[$i]->getAnnee()->getDateDebut()->format("d-m-Y H:i:s").'-'.$cm[$i]->getAnnee()->getDateFin()->format("d-m-Y H:i:s");
                    $tabNAV[$k]['professeur']=$cm[$i]->getProfesseur()->getNom().'-'.$cm[$i]->getProfesseur()->getPrenom();
                    $tabNAV[$k]['classe']=$cm[$i]->getTypeClasse()->getClasse()->getLibelle().' '.$cm[$i]->getTypeClasse()->getLibelle();
                    $tabNAV[$k]['matiere']=$cm[$i]->getClasseMatiere()->getMatiere()->getLibelle();
                    $tabNAV[$k]['coefficient']=$cm[$i]->getClasseMatiere()->getCoefficient();
                    $tabNAV[$k]['effectif']=sizeof($em->getRepository(EleveTypeClasse::class)->findBy(array('type_classe'=>$cm[$i]->getTypeClasse()->getId())));
                    $tabNAV[$k]['idCm']=$cm[$i]->getId();
                    $tabNAV[$k]['periode']=$typeNV[$j]['nom_periode'];
                    $datetimer=substr($typeNV[$j]['dateCreation'],0,13);
                    $note=$em->getRepository(Note::class)->typeNoteNV($cm[$i]->getId(),$datetimer);
                    $tabNAV[$k]['note'][$j]['param']=$datetimer;
                    $tabNAV[$k]['note'][$j]['type']=$typeNV[$j]['type'];
                    $tabNAV[$k]['note'][$j]['date']=$typeNV[$j]['dateCreation'];

                    for ($m=0;$m<sizeof($note);$m++)
                    {
                        $tabNAV[$k]['note'][$j]['noteDetails'][$m]['eleve']=$note[$m]['nom']." ".$note[$m]['prenom'];
                        $tabNAV[$k]['note'][$j]['noteDetails'][$m]['note']=$note[$m]['note'];
                       $tabNAV[$k]['note'][$j]['noteDetails'][$m]['obs']=$this->getObservation($note[$m]['note']);
                    }
                }
                $k++;
            }

        }
        return $tabNAV;
    }

    private function  noteRejet($cm)
    {
        $em=$this->getDoctrine()->getManager();
        $tabNAV=array();
        $k=0;
        for ($i=0;$i<sizeof($cm);$i++)
        {
            $typeNV=$em->getRepository(Note::class)->typeR($cm[$i]->getId());

            if($typeNV != null)
            {



                for ($j=0;$j<sizeof($typeNV);$j++)
                {
                    $tabNAV[$k]['annee']=$cm[$i]->getAnnee()->getDateDebut()->format("d-m-Y H:i:s").'-'.$cm[$i]->getAnnee()->getDateFin()->format("d-m-Y H:i:s");
                    $tabNAV[$k]['professeur']=$cm[$i]->getProfesseur()->getNom().'-'.$cm[$i]->getProfesseur()->getPrenom();
                    $tabNAV[$k]['classe']=$cm[$i]->getTypeClasse()->getClasse()->getLibelle().' '.$cm[$i]->getTypeClasse()->getLibelle();
                    $tabNAV[$k]['matiere']=$cm[$i]->getClasseMatiere()->getMatiere()->getLibelle();
                    $tabNAV[$k]['coefficient']=$cm[$i]->getClasseMatiere()->getCoefficient();
                    $tabNAV[$k]['effectif']=sizeof($em->getRepository(EleveTypeClasse::class)->findBy(array('type_classe'=>$cm[$i]->getTypeClasse()->getId())));
                    $tabNAV[$k]['idCm']=$cm[$i]->getId();
                    $tabNAV[$k]['periode']=$typeNV[$j]['nom_periode'];
                    $datetimer=substr($typeNV[$j]['dateCreation'],0,13);
                    $note=$em->getRepository(Note::class)->typeNoteR($cm[$i]->getId(),$datetimer);

                    $tabNAV[$k]['note'][$j]['type']=$typeNV[$j]['type'];
                    $tabNAV[$k]['note'][$j]['param']=$datetimer;
                    $tabNAV[$k]['note'][$j]['date']=$typeNV[$j]['dateCreation'];

                    for ($m=0;$m<sizeof($note);$m++)
                    {
                        $tabNAV[$k]['note'][$j]['noteDetails'][$m]['eleve']=$note[$m]['nom']." ".$note[$m]['prenom'];
                        $tabNAV[$k]['note'][$j]['noteDetails'][$m]['note']=$note[$m]['note'];
                        $tabNAV[$k]['note'][$j]['noteDetails'][$m]['obs']=$this->getObservation($note[$m]['note']);
                    }
                }
                $k++;
            }

        }
        return $tabNAV;
    }

    /**
     * Lists all notes entities.
     *
     * @Route("/notes/validation/{idCm}/{date_param}", name="notes_validation")
     * @Method("GET")
     */
    public  function ValiderNoteAction($idCm,$date_param)
    {

        $em=$this->getDoctrine()->getManager();
        $notes =$em->getRepository(Note::class)->typeNoteNV2($idCm,$date_param);

        for ($i=0; $i<sizeof($notes);$i++)
        {
            $actif=$em->getRepository(Note::class)->find($notes[$i]['id']);
            $actif->setStatut('validé');
            $em->persist($actif);
        }
        $em->flush();
       return $this->redirectToRoute('notes_index',array('id'=>1));
    }

    private function getParent($note)
    {
        $matiere=$note->getClasseMatiereProfesseurAnnee()->getClasseMatiere()->getMatiere()->getLibelle();
        $classe=$note->getClasseMatiereProfesseurAnnee()->getTypeClasse()->getClasse()->getLibelle()." ".$note->getClasseMatiereProfesseurAnnee()->getTypeClasse()->getLibelle();
        $prof=$note->getClasseMatiereProfesseurAnnee()->getProfesseur()->getNom()." ".$note->getClasseMatiereProfesseurAnnee()->getProfesseur()->getPrenom();
        $em=$this->getDoctrine()->getManager();   
        $elve_parents=$em->getRepository(EleveParent::class)->findBy(array('eleve' => $note->getEleve()->getId()));
        foreach ($elve_parents as $item) {
            $parent=$item->getParent();
            $notif=new NotificationCenseur();
            $notif->setMessage("Eleve : ".$note->getEleve()->getNom()." ".$note->getEleve()->getPrenom()." Matière : ".$matiere." Professeur : ".$prof." Note : ".$note->getNote());
            $notif->setDateCreation(new \Datetime());
            $notif->setDestinataire($parent->getId());
            $notif->setEmetteur($this->getUser()->getEcole()->getCenseur()->getId());
        }
    }

    /**
     * Lists all notes entities.
     *
     * @Route("/notes/rejet/{idCm}/{date_param}", name="notes_rejet")
     * @Method("GET")
     */
    public  function RejetNoteAction($idCm,$date_param)
    {
        $em=$this->getDoctrine()->getManager();
        $notes =$em->getRepository(Note::class)->typeNoteNV($idCm,$date_param);

        for ($i=0; $i<sizeof($notes);$i++)
        {
            $actif=$em->getRepository(Note::class)->find($notes[$i]['id']);
            $actif->setStatut('non validé');
            $em->persist($actif);
        }

        $em->flush();
        return $this->redirectToRoute('notes_index',array('id'=>2));
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
}
