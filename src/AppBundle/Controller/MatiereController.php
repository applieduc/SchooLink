<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Matiere;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Matiere controller.
 *
 * @Route("matiere")
 */
class MatiereController extends Controller
{
    /**
     * Lists all matiere entities.
     *
     * @Route("/", name="matiere_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $matieres = $em->getRepository('AppBundle:Matiere')->findAll();

        return $this->render('matiere/index.html.twig', array(
            'matieres' => $matieres,
        ));
    }

    /**
     * Creates a new matiere entity.
     *
     * @Route("/new", name="matiere_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $matiere = new Matiere();
        $form = $this->createForm('AppBundle\Form\MatiereType', $matiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($matiere);
            $em->flush();

            return $this->redirectToRoute('matiere_index');
//            return $this->redirectToRoute('matiere_show', array('id' => $matiere->getId()));
        }

        return $this->render('matiere/new.html.twig', array(
            'matiere' => $matiere,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a matiere entity.
     *
     * @Route("/{id}", name="matiere_show")
     * @Method("GET")
     */
    public function showAction(Matiere $matiere)
    {
        $deleteForm = $this->createDeleteForm($matiere);

        return $this->render('matiere/show.html.twig', array(
            'matiere' => $matiere,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing matiere entity.
     *
     * @Route("/{id}/edit", name="matiere_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Matiere $matiere)
    {
        $deleteForm = $this->createDeleteForm($matiere);
        $editForm = $this->createForm('AppBundle\Form\MatiereType', $matiere);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('matiere_edit', array('id' => $matiere->getId()));
        }

        return $this->render('matiere/edit.html.twig', array(
            'matiere' => $matiere,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a matiere entity.
     *
     * @Route("/{id}", name="matiere_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Matiere $matiere)
    {
        $form = $this->createDeleteForm($matiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($matiere);
            $em->flush();
        }

        return $this->redirectToRoute('matiere_index');
    }

    /**
     * Creates a form to delete a matiere entity.
     *
     * @param Matiere $matiere The matiere entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Matiere $matiere)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('matiere_delete', array('id' => $matiere->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    /**
     * Displays a form to edit an existing classe entity.
     *
     * @Route("/{id}/archive", name="matiere_archive")
     * @Method({"GET", "POST"})
     */
    public function archiveAction(Request $request, Matiere $matiere)
    {
        $em=$this->getDoctrine()->getManager();
        $matiere->setArchiver(1);
        $em->persist($matiere);
        $em->flush();
        return $this->redirectToRoute('matiere_index');
    }
    /**
     * Displays a form to edit an existing classe entity.
     *
     * @Route("/{id}/desarchive", name="matiere_desarchive")
     * @Method({"GET", "POST"})
     */
    public function desarchiveAction(Request $request, Matiere $matiere)
    {
        $em=$this->getDoctrine()->getManager();
        $matiere->setArchiver(0);
        $em->persist($matiere);
        $em->flush();
        return $this->redirectToRoute('matiere_index');
    }
}
