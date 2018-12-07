<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Censeur;
use AppBundle\Entity\Classe;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Classe controller.
 *
 * @Route("classe")
 */
class ClasseController extends Controller
{
    /**
     * Displays a form to edit an existing classe entity.
     *
     * @Route("/{id}/edit", name="classe_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Classe $classe)
    {

        $editForm = $this->createForm('AppBundle\Form\ClasseType', $classe);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('classe_edit', array('id' => $classe->getId()));
        }

        return $this->render('classe/edit.html.twig', array(
            'classe' => $classe,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a new classe entity.
     *
     * @Route("/new", name="classe_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {

        $classe = new Classe();
        $form = $this->createForm('AppBundle\Form\ClasseType', $classe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $censeur=$em->getRepository(Censeur::class)->find($this->getUser()->getId());

            $classe->setEcole($censeur->getEcole());
            $em->persist($classe);
            $em->flush();

            return $this->redirectToRoute('classe_show', array('id' => $classe->getId()));
        }

        return $this->render('classe/new.html.twig', array(
            'classe' => $classe,
            'form' => $form->createView(),
        ));
    }

    /**
     * Lists all classe entities.
     *
     * id=annee_id
     * @Route("/{id}", name="classe_index")
     * @Method("GET")
     */
    public function indexAction($id=null,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session=$this->get("session");
        if($id!=null){
            $annee = $em->getRepository('AppBundle:Annee')->find((int)$id);
            $session->set('annee',$annee);

        }else{
            $session->remove('annee');
        }
        $censeur=$em->getRepository(Censeur::class)->find($this->getUser()->getId());


        $classes = $em->getRepository('AppBundle:Classe')->findBy(array('ecole'=>$censeur->getEcole()->getId()));

        return $this->render('classe/index.html.twig', array(
            'classes' => $classes,
        ));
    }

    /**
     * Finds and displays a classe entity.
     *
     * @Route("/{id}", name="classe_show")
     * @Method("GET")
     */
    public function showAction(Classe $classe)
    {

        return $this->render('classe/show.html.twig', array(
            'classe' => $classe,
        ));
    }
    /**
     * Displays a form to edit an existing classe entity.
     *
     * @Route("/{id}/archive", name="classe_archive")
     * @Method({"GET", "POST"})
     */
    public function archiveAction(Request $request, Classe $classe)
    {
        $em=$this->getDoctrine()->getManager();
        $classe->setArchiver(1);
        $em->persist($classe);
        $em->flush();
        $session=$this->get("session");
        $annee= $session->get('annee');
        return $this->redirectToRoute('classe_index',array('id'=>$annee->getId()));
    }
    /**
     * Displays a form to edit an existing classe entity.
     *
     * @Route("/{id}/desarchive", name="classe_desarchive")
     * @Method({"GET", "POST"})
     */
    public function desarchiveAction(Request $request, Classe $classe)
    {
        $em=$this->getDoctrine()->getManager();
        $classe->setArchiver(0);
        $em->persist($classe);
        $em->flush();
        $session=$this->get("session");
        $annee= $session->get('annee');
        return $this->redirectToRoute('classe_index',array('id'=>$annee->getId()));
  
    }
}
