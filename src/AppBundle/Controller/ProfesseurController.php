<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Professeur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Professeur controller.
 *
 * @Route("ecole/professeur")
 */
class ProfesseurController extends Controller
{
    /**
     * Lists all professeur entities.
     *
     * @Route("/", name="ecole_professeur_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $professeurs = $em->getRepository('AppBundle:Professeur')->findAll();

        return $this->render('professeur/index.html.twig', array(
            'professeurs' => $professeurs,
        ));
    }

    /**
     * Creates a new professeur entity.
     *
     * @Route("/new", name="ecole_professeur_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $professeur = new Professeur();
        $form = $this->createForm('AppBundle\Form\ProfesseurType', $professeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($professeur);
            $em->flush();

            return $this->redirectToRoute('ecole_professeur_show', array('id' => $professeur->getId()));
        }

        return $this->render('professeur/new.html.twig', array(
            'professeur' => $professeur,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a professeur entity.
     *
     * @Route("/{id}", name="ecole_professeur_show")
     * @Method("GET")
     */
    public function showAction(Professeur $professeur)
    {
        $deleteForm = $this->createDeleteForm($professeur);

        return $this->render('professeur/show.html.twig', array(
            'professeur' => $professeur,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing professeur entity.
     *
     * @Route("/{id}/edit", name="ecole_professeur_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Professeur $professeur)
    {
        $deleteForm = $this->createDeleteForm($professeur);
        $editForm = $this->createForm('AppBundle\Form\ProfesseurType', $professeur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ecole_professeur_edit', array('id' => $professeur->getId()));
        }

        return $this->render('professeur/edit.html.twig', array(
            'professeur' => $professeur,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a professeur entity.
     *
     * @Route("/{id}", name="ecole_professeur_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Professeur $professeur)
    {
        $form = $this->createDeleteForm($professeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($professeur);
            $em->flush();
        }

        return $this->redirectToRoute('ecole_professeur_index');
    }

    /**
     * Creates a form to delete a professeur entity.
     *
     * @param Professeur $professeur The professeur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Professeur $professeur)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ecole_professeur_delete', array('id' => $professeur->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
