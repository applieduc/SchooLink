<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ecole;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Ecole controller.
 *
 * @Route("/adminstration/ecole")
 */
class EcoleController extends Controller
{
    /**
     * Lists all ecole entities.
     *
     * @Route("/", name="ecole_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $ecoles = $em->getRepository('AppBundle:Ecole')->findAll();

        return $this->render('ecole/index.html.twig', array(
            'ecoles' => $ecoles,
        ));
    }

    /**
     * Creates a new ecole entity.
     *
     * @Route("/new", name="ecole_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $ecole = new Ecole();
        $form = $this->createForm('AppBundle\Form\EcoleType', $ecole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $code = strtoupper("ET" . substr(sha1(uniqid(mt_rand(), true)), 0, 8));
           $ecole->setCodeTelephone($code);
            $em = $this->getDoctrine()->getManager();
            $em->persist($ecole);
            $em->flush();

            return $this->redirectToRoute('ecole_show', array('id' => $ecole->getId()));
        }

        return $this->render('ecole/new.html.twig', array(
            'ecole' => $ecole,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ecole entity.
     *
     * @Route("/{id}", name="ecole_show")
     * @Method("GET")
     */
    public function showAction(Ecole $ecole)
    {
        $deleteForm = $this->createDeleteForm($ecole);

        return $this->render('ecole/show.html.twig', array(
            'ecole' => $ecole,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ecole entity.
     *
     * @Route("/{id}/edit", name="ecole_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Ecole $ecole)
    {
        $deleteForm = $this->createDeleteForm($ecole);
        $editForm = $this->createForm('AppBundle\Form\EcoleType', $ecole);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ecole_edit', array('id' => $ecole->getId()));
        }

        return $this->render('ecole/edit.html.twig', array(
            'ecole' => $ecole,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ecole entity.
     *
     * @Route("/{id}", name="ecole_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Ecole $ecole)
    {
        $form = $this->createDeleteForm($ecole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ecole);
            $em->flush();
        }

        return $this->redirectToRoute('ecole_index');
    }

    /**
     * Creates a form to delete a ecole entity.
     *
     * @param Ecole $ecole The ecole entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Ecole $ecole)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ecole_delete', array('id' => $ecole->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
