<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ClasseMatiereProfesseurAnnee;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Classematiereprofesseurannee controller.
 *
 * @Route("enseignement")
 */
class ClasseMatiereProfesseurAnneeController extends Controller
{
    /**
     * Lists all classeMatiereProfesseurAnnee entities.
     *
     * @Route("/", name="classematiereprofesseurannee_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $classeMatiereProfesseurAnnees = $em->getRepository('AppBundle:ClasseMatiereProfesseurAnnee')->findAll();

        return $this->render('classematiereprofesseurannee/index.html.twig', array(
            'classeMatiereProfesseurAnnees' => $classeMatiereProfesseurAnnees,
        ));
    }

    /**
     * Lists all classeMatiereProfesseurAnnee entities.
     *
     * @Route("/infos-prof", name="enseignement_index2")
     * @Method("GET")
     */
    public function testAction()
    {
        $em = $this->getDoctrine()->getManager();

    $profInfos = $em->getRepository('AppBundle:Professeur')->EtablisementProf();

        return $this->render('classematiereprofesseurannee/index.html.twig', array(
            'profinfos' => $profInfos,
        ));
    }


    /**
     * Creates a new classeMatiereProfesseurAnnee entity.
     *
     * @Route("/{id}/new", name="enseignement_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $classeMatiereProfesseurAnnee = new Classematiereprofesseurannee();
        $form = $this->createForm('AppBundle\Form\ClasseMatiereProfesseurAnneeType', $classeMatiereProfesseurAnnee,['user'=>$this->getUser(),'nature'=>'prof']);
         $class_matiere = $em->getRepository('AppBundle:ClasseMatiere')->findOneBy(['id'=>$id]);
       //  var_dump()
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 
           $classeMatiereProfesseurAnnee->setClasseMatiere($class_matiere);
         //  $classeMatiereProfesseurAnnee->set
            $em->persist($classeMatiereProfesseurAnnee);
            $em->flush();

            return $this->redirectToRoute('enseignement_show', array('id' => $classeMatiereProfesseurAnnee->getId()));
        }

        return $this->render('classematiereprofesseurannee/new.html.twig', array(
            'classeMatiereProfesseurAnnee' => $classeMatiereProfesseurAnnee,
            'class_matiere' => $class_matiere,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new classeMatiereProfesseurAnnee entity.
     *
     * @Route("/new/professeur/{id}", name="enseignement_prof_new")
     * @Method({"GET", "POST"})
     */
    public function enseignementAction(Request $request, $id)
    {
        $session = new  Session();
        $anne = $session->get('annee');
        $em = $this->getDoctrine()->getManager();

        $classeMatiereProfesseurAnnee = new Classematiereprofesseurannee();
        $form = $this->createForm('AppBundle\Form\ClasseMatiereProfesseurAnneeType', $classeMatiereProfesseurAnnee,['user'=>$this->getUser(),'nature'=>'matiere' ]);
        $prof = $em->getRepository('AppBundle:Professeur')->findOneBy(['id'=>$id]);
        $classes = $em->getRepository('AppBundle:Classe')->findBy(['ecole'=>$this->getUser()->getEcole()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $classeMatiereProfesseurAnnee->setProfesseur($prof);
            // $classeMatiereProfesseurAnnee->setAnnee($anne);
            $typeclasse = $em->getRepository('AppBundle:TypeClasse')->findOneBy(['id'=>$request->get('typeclasse')]);
            $classeMatiereProfesseurAnnee->setTypeClasse($typeclasse);
            $em->persist($classeMatiereProfesseurAnnee);
            $em->flush();

            return $this->redirectToRoute('enseignement_prof_new', array('id' => $id));
        }

        return $this->render('classematiereprofesseurannee/enseignement.html.twig', array(
            'classeMatiereProfesseurAnnee' => $classeMatiereProfesseurAnnee,
            'professeur' => $prof,
            'classes'=>$classes,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a classeMatiereProfesseurAnnee entity.
     *
     * @Route("/{id}", name="enseignement_show")
     * @Method("GET")
     */
    public function showAction(ClasseMatiereProfesseurAnnee $classeMatiereProfesseurAnnee)
    {
        $deleteForm = $this->createDeleteForm($classeMatiereProfesseurAnnee);

        return $this->render('classematiereprofesseurannee/show.html.twig', array(
            'classeMatiereProfesseurAnnee' => $classeMatiereProfesseurAnnee,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing classeMatiereProfesseurAnnee entity.
     *
     * @Route("/{id}/edit", name="classematiereprofesseurannee_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ClasseMatiereProfesseurAnnee $classeMatiereProfesseurAnnee)
    {
        $deleteForm = $this->createDeleteForm($classeMatiereProfesseurAnnee);
        $editForm = $this->createForm('AppBundle\Form\ClasseMatiereProfesseurAnneeType', $classeMatiereProfesseurAnnee);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('classematiereprofesseurannee_edit', array('id' => $classeMatiereProfesseurAnnee->getId()));
        }

        return $this->render('classematiereprofesseurannee/edit.html.twig', array(
            'classeMatiereProfesseurAnnee' => $classeMatiereProfesseurAnnee,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a classeMatiereProfesseurAnnee entity.
     *
     * @Route("/{id}", name="classematiereprofesseurannee_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ClasseMatiereProfesseurAnnee $classeMatiereProfesseurAnnee)
    {
        $form = $this->createDeleteForm($classeMatiereProfesseurAnnee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($classeMatiereProfesseurAnnee);
            $em->flush();
        }

        return $this->redirectToRoute('classematiereprofesseurannee_index');
    }

    /**
     * Creates a form to delete a classeMatiereProfesseurAnnee entity.
     *
     * @param ClasseMatiereProfesseurAnnee $classeMatiereProfesseurAnnee The classeMatiereProfesseurAnnee entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ClasseMatiereProfesseurAnnee $classeMatiereProfesseurAnnee)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('classematiereprofesseurannee_delete', array('id' => $classeMatiereProfesseurAnnee->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
