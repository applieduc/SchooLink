<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Censeur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Censeur controller.
 *
 * @Route("censeur")
 */
class CenseurController extends Controller
{
    /**
     * Lists all censeur entities.
     *
     * @Route("/", name="censeur_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $censeurs = $em->getRepository('AppBundle:Censeur')->findAll();

        return $this->render('censeur/index.html.twig', array(
            'censeurs' => $censeurs,
        ));
    }

    /**
     * Creates a new censeur entity.
     *
     * @Route("/new", name="censeur_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $censeur = new Censeur();

        $form = $this->createForm('AppBundle\Form\CenseurType', $censeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $censeur->setRoles(array('ROLE_CENSEUR'));

              $file = $censeur->getPhoto();

            if ($file) {
                $fileName = $this->get('app.file_uploader')->upload($file);
                $censeur->setPhoto($fileName);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($censeur);
            $em->flush();

            return $this->redirectToRoute('censeur_show', array('id' => $censeur->getId()));
        }

        return $this->render('censeur/new.html.twig', array(
            'censeur' => $censeur,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a censeur entity.
     *
     * @Route("/{id}", name="censeur_show")
     * @Method("GET")
     */
    public function showAction(Censeur $censeur)
    {
        $deleteForm = $this->createDeleteForm($censeur);

        return $this->render('censeur/show.html.twig', array(
            'censeur' => $censeur,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing censeur entity.
     *
     * @Route("/{id}/edit", name="censeur_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Censeur $censeur)
    {
        $deleteForm = $this->createDeleteForm($censeur);
        $editForm = $this->createForm('AppBundle\Form\CenseurType', $censeur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('censeur_edit', array('id' => $censeur->getId()));
        }

        return $this->render('censeur/edit.html.twig', array(
            'censeur' => $censeur,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a censeur entity.
     *
     * @Route("/{id}", name="censeur_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Censeur $censeur)
    {
        $form = $this->createDeleteForm($censeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($censeur);
            $em->flush();
        }

        return $this->redirectToRoute('censeur_index');
    }

    /**
     * Creates a form to delete a censeur entity.
     *
     * @param Censeur $censeur The censeur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Censeur $censeur)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('censeur_delete', array('id' => $censeur->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
