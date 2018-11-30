<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ClasseMatiere;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Classematiere controller.
 *
 * @Route("class_mat")
 */
class ClasseMatiereController extends Controller
{
    /**
     * Lists all classeMatiere entities.
     *
     * @Route("/", name="class_mat_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $classeMatieres = $em->getRepository('AppBundle:ClasseMatiere')->findAll();

        return $this->render('classematiere/index.html.twig', array(
            'classeMatieres' => $classeMatieres,
        ));
    }

    /**
     * Creates a new classeMatiere entity.
     *
     * @Route("/new", name="class_mat_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $classeMatiere = new Classematiere();
        $form = $this->createForm('AppBundle\Form\ClasseMatiereType', $classeMatiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($classeMatiere);
            $em->flush();

            return $this->redirectToRoute('class_mat_show', array('id' => $classeMatiere->getId()));
        }

        return $this->render('classematiere/new.html.twig', array(
            'classeMatiere' => $classeMatiere,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a classeMatiere entity.
     *
     * @Route("/{id}", name="class_mat_show")
     * @Method("GET")
     */
    public function showAction(ClasseMatiere $classeMatiere)
    {
        $deleteForm = $this->createDeleteForm($classeMatiere);

        return $this->render('classematiere/show.html.twig', array(
            'classeMatiere' => $classeMatiere,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing classeMatiere entity.
     *
     * @Route("/{id}/edit", name="class_mat_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ClasseMatiere $classeMatiere)
    {
        $deleteForm = $this->createDeleteForm($classeMatiere);
        $editForm = $this->createForm('AppBundle\Form\ClasseMatiereType', $classeMatiere);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('class_mat_edit', array('id' => $classeMatiere->getId()));
        }

        return $this->render('classematiere/edit.html.twig', array(
            'classeMatiere' => $classeMatiere,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
 /**
     * Displays a form to edit an existing classeMatiere entity.
     *
     * @Route("/{id}/archive", name="class_mat_archive")
     * @Method({"GET", "POST"})
     */
    public function archiveAction(Request $request, ClasseMatiere $classeMatiere)
    {
        $em=$this->getDoctrine()->getManager();
        $classeMatiere->setArchiver(1);
        $em->persist($classeMatiere);
        $em->flush();
        return $this->redirectToRoute('class_mat_index');
    }
    /**
     * Displays a form to edit an existing classeMatiere entity.
     *
     * @Route("/{id}/desarchive", name="class_mat_desarchive")
     * @Method({"GET", "POST"})
     */
    public function desarchiveAction(Request $request, ClasseMatiere $classeMatiere)
    {
        $em=$this->getDoctrine()->getManager();
        $classeMatiere->setArchiver(0);
        $em->persist($classeMatiere);
        $em->flush();
        return $this->redirectToRoute('class_mat_index');
    }

    /**
     * Deletes a classeMatiere entity.
     *
     * @Route("/{id}", name="class_mat_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ClasseMatiere $classeMatiere)
    {
        $form = $this->createDeleteForm($classeMatiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($classeMatiere);
            $em->flush();
        }

        return $this->redirectToRoute('class_mat_index');
    }

    /**
     * Creates a form to delete a classeMatiere entity.
     *
     * @param ClasseMatiere $classeMatiere The classeMatiere entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ClasseMatiere $classeMatiere)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('class_mat_delete', array('id' => $classeMatiere->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
