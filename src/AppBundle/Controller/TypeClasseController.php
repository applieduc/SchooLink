<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TypeClasse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Typeclasse controller.
 *
 * @Route("typeclasse")
 */
class TypeClasseController extends Controller
{
    /**
     * Lists all typeClasse entities.
     *
     * @Route("/", name="typeclasse_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $typeClasses = $em->getRepository('AppBundle:TypeClasse')->findAll();

        return $this->render('typeclasse/index.html.twig', array(
            'typeClasses' => $typeClasses,
        ));
    }

    /**
     * Creates a new typeClasse entity.
     *
     * @Route("/new", name="typeclasse_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $typeClasse = new Typeclasse();
        $form = $this->createForm('AppBundle\Form\TypeClasseType', $typeClasse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($typeClasse);
            $em->flush();

            return $this->redirectToRoute('typeclasse_show', array('id' => $typeClasse->getId()));
        }

        return $this->render('typeclasse/new.html.twig', array(
            'typeClasse' => $typeClasse,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a typeClasse entity.
     *
     * @Route("/{id}", name="typeclasse_show")
     * @Method("GET")
     */
    public function showAction(TypeClasse $typeClasse)
    {
        $deleteForm = $this->createDeleteForm($typeClasse);

        return $this->render('typeclasse/show.html.twig', array(
            'typeClasse' => $typeClasse,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing typeClasse entity.
     *
     * @Route("/{id}/edit", name="typeclasse_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TypeClasse $typeClasse)
    {
        $deleteForm = $this->createDeleteForm($typeClasse);
        $editForm = $this->createForm('AppBundle\Form\TypeClasseType', $typeClasse);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('typeclasse_edit', array('id' => $typeClasse->getId()));
        }

        return $this->render('typeclasse/edit.html.twig', array(
            'typeClasse' => $typeClasse,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a typeClasse entity.
     *
     * @Route("/{id}", name="typeclasse_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, TypeClasse $typeClasse)
    {
        $form = $this->createDeleteForm($typeClasse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($typeClasse);
            $em->flush();
        }

        return $this->redirectToRoute('typeclasse_index');
    }

    /**
     * Creates a form to delete a typeClasse entity.
     *
     * @param TypeClasse $typeClasse The typeClasse entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TypeClasse $typeClasse)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('typeclasse_delete', array('id' => $typeClasse->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
