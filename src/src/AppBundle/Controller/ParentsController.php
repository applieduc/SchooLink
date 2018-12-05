<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Parents;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Parent controller.
 *
 * @Route("parents")
 */
class ParentsController extends Controller
{
    /**
     * Lists all parent entities.
     *
     * @Route("/", name="parents_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $parents = $em->getRepository('AppBundle:Parents')->findAll();

        return $this->render('parents/index.html.twig', array(
            'parents' => $parents,
        ));
    }

    /**
     * Creates a new parent entity.
     *
     * @Route("/new", name="parents_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $parent = new Parent();
        $form = $this->createForm('AppBundle\Form\ParentsType', $parent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($parent);
            $em->flush();

            return $this->redirectToRoute('parents_show', array('id' => $parent->getId()));
        }

        return $this->render('parents/new.html.twig', array(
            'parent' => $parent,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a parent entity.
     *
     * @Route("/{id}", name="parents_show")
     * @Method("GET")
     */
    public function showAction(Parents $parent)
    {
        $deleteForm = $this->createDeleteForm($parent);

        return $this->render('parents/show.html.twig', array(
            'parent' => $parent,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing parent entity.
     *
     * @Route("/{id}/edit", name="parents_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Parents $parent)
    {
        $deleteForm = $this->createDeleteForm($parent);
        $editForm = $this->createForm('AppBundle\Form\ParentsType', $parent);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('parents_edit', array('id' => $parent->getId()));
        }

        return $this->render('parents/edit.html.twig', array(
            'parent' => $parent,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a parent entity.
     *
     * @Route("/{id}", name="parents_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Parents $parent)
    {
        $form = $this->createDeleteForm($parent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($parent);
            $em->flush();
        }

        return $this->redirectToRoute('parents_index');
    }

    /**
     * Creates a form to delete a parent entity.
     *
     * @param Parents $parent The parent entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Parents $parent)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('parents_delete', array('id' => $parent->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
