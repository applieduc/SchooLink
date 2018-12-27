<?php

namespace AppBundle\Controller;
use AppBundle\Entity\Censeur;

use AppBundle\Entity\Ecole;
use AppBundle\Entity\Annee;
use AppBundle\Entity\Eleve;
use AppBundle\Form\CenseurProfType;
use AppBundle\Form\EcoleType;
use AppBundle\Entity\EleveClasseEcoleAnnee;
use AppBundle\Entity\NotificationProfesseur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;;

class DefaultController extends Controller
{


      /**
     * @Route("/year", name="active")
     */
    public function activeAction(Request $request)
    {
        
        $em=$this->getDoctrine()->getManager();
        if($request->get('year')!="0"){
            $annee=$em->getRepository(Annee::class)->find((int)$request->get('year'));
        }else{
            $annee=$em->getRepository(Annee::class)->findOneBy(array('ecole'=>$this->getUser()->getEcole()->getId()));
        }
       
        $this->get('session')->set('annee',$annee);
        return $this->redirect();
    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need

        return $this->redirect('/login');
    }
    /**
     * @Route("/getNotif", name="getNotif")
     */
    public function getNotifAction(Request $request)
    {
        
        $em=$this->getDoctrine()->getManager();
        $censeur=$em->getRepository(Censeur::class)->find($this->getUser()->getId());
        $noti=$em->getRepository(NotificationProfesseur::class)->findBy(array('destinataire'=>$censeur->getId(),'vu'=>false));
        return new Response(json_encode(array('nb'=>sizeof($noti))));
    }

     /**
     * @Route("/show_notif", name="show_notif")
     */
    public function show_notifAction(Request $request)
    {

        $em=$this->getDoctrine()->getManager();
        $censeur=$em->getRepository(Censeur::class)->find($this->getUser()->getId());
        $annees=$em->getRepository(Annee::class)->findBy(array('ecole'=>$censeur->getEcole()->getId()),array('id'=>'DESC'));
        $noti=$em->getRepository(NotificationProfesseur::class)->findBy(array('destinataire'=>$censeur->getId()));
        foreach($noti as $n){
            $n->setVu(true);
            $em->persist($n);
            $em->flush();
        }
        // replace this example code with whatever you need
        return $this->render('AppBundle:Default:noti.html.twig', array('noti' => $noti ));
    }

   /**
     * Finds and displays a ecole entity.
     *
     * @Route("/{id}/profil", name="profil_ecole_show")
     * @Method("GET")
     */
    public function showAction(Ecole $ecole)
    {
        $em = $this->getDoctrine()->getManager();
        $annee=$em->getRepository(Annee::class)->findOneBy(array('ecole'=>$ecole->getId(),'cloture'=>0));
        if($annee!=null){
            $eleve=   $em->getRepository(EleveClasseEcoleAnnee::class)->findBy(array('ecole' => $ecole->getId(),'annee'=>$annee->getId() ));
        
            $s_eleve=sizeof($eleve);
        }
       
        $s_eleve=0;
        return $this->render('default/show.html.twig', array(
            'ecole' => $ecole,
            "nb_eleve"=>$s_eleve,
        ));
    }

/**
     * Displays a form to edit an existing censeur entity.
     *
     * @Route("/{id}/edit_profil", name="profil_censeur_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Censeur $censeur)
    {
        $editForm = $this->createForm('AppBundle\Form\CenseurProfType', $censeur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $file = $censeur->getPhoto();
            if ($file) {
                $fileName = $this->get('app.file_uploader')->upload($file);
                $censeur->setPhoto($fileName);


            }else{

                $censeur->setPhoto('avatar.png');
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profil_ecole_show',array('id'=>$censeur->getEcole()->getId()));
        }

        return $this->render('default/edit.html.twig', array(
            'censeur' => $censeur,
            'edit_form' => $editForm->createView(),
        ));
    }


       /**
     * Displays a form to edit an existing ecole entity.
     *
     * @Route("/{id}/edit_cole", name="profil_ecole_edit")
     * @Method({"GET", "POST"})
     */
    public function editEcoleAction(Request $request, Ecole $ecole)
    {
        $editForm = $this->createForm('AppBundle\Form\EcoleType', $ecole);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $file = $ecole->getLogo();
            if ($file) {
                $fileName = $this->get('app.file_uploader')->upload($file);
                $ecole->setLogo($fileName);


            }else{

                $ecole->setLogo('ecole.png');
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profil_ecole_show',array('id'=>$ecole->getId()));
        }
      

        return $this->render('default/update_ecole.html.twig', array(
            'ecole' => $ecole,
            'edit_form' => $editForm->createView(),
        ));
        
    }
    
}
