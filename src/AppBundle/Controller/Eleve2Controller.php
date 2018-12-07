<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Censeur;
use AppBundle\Entity\Classe;
use AppBundle\Entity\Eleve;
use AppBundle\Entity\EleveTypeClasse;
use AppBundle\Entity\TypeClasse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Eleve controller.
 *
 * @Route("eleve_type")
 */
class Eleve2Controller extends Controller
{
    /**
     * Lists all eleve entities.
     *
     * @Route("/{classeTpe}", name="eleve_type_index")
     * @Method("GET")
     */
    public function indexAction(TypeClasse $classeTpe)
    {
        $em = $this->getDoctrine()->getManager();
        $eleve_type=$em->getRepository(EleveTypeClasse::class)->findBy(array('type_classe'=>$classeTpe->getId()));
        return $this->render('AppBundle:EleveClasse:eleve_index.html.twig', array(
            'eleves_type' => $eleve_type,
            'classeType'=>$classeTpe,

        ));
    }

    /**
     * Creates a new eleve entity.
     *
     * @Route("/new", name="eleve_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $eleve = new Eleve();
        $form = $this->createForm('AppBundle\Form\EleveType', $eleve);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $censeur=$em->getRepository(Censeur::class)->find($this->getUser()->getId());
            $eleve->setEcole($censeur->getEcole());
            $eleve->setClasse($em->getRepository(Classe::class)->find((int)$request->get('classe')));
            $em->persist($eleve);
            $em->flush();

            return $this->redirectToRoute('eleve_index');

//            return $this->redirectToRoute('eleve_show', array('id' => $eleve->getId()));
        }
        $censeur=$em->getRepository(Censeur::class)->find($this->getUser()->getId());
        $classes=$em->getRepository(Classe::class)->findBy(array('ecole'=>$censeur->getEcole()->getId()));

        return $this->render('eleve/new.html.twig', array(
            'eleve' => $eleve,
            'classes' => $classes,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a eleve entity.
     *
     * @Route("/new_eleve_in_classe/{classeType}", name="new_eleve_in_classe")
     * @Method("GET")
     */
    public function showAction(TypeClasse $typeClasse)
    {

        $em=$this->getDoctrine()->getManager();
        return $this->render('AppBundle:EleveClasse:eleve_index.html.twig', array(
            'eleves' => ,
            'classeType'=>$classeTpe,

        ));
    }

    private function get_eleve($classe){
        $annee=$this->get('session')->get('annee');
        $em = $this->getDoctrine()->getManager();
        $eleves = $em->getRepository('AppBundle:Eleve')->findBy(array('archiver' =>false,'classe'=>$classe));

        $eleves_not_classe=array();
        $i=0;
        foreach ($eleves as $val) {
            $eleve = $em->getRepository('AppBundle:EleveTypeClasse')->findOneBy(array('eleve' =>$val->getId(),'archiver'=>false));

            if($eleve!=null && $eleve->getDateCreation() <$annee->getDateDebut()){

                $eleves_not_classe[$i]=$val;
                $i++;
            }elseif ($eleve==null){
                $eleves_not_classe[$i]=$val;
                $i++;
            }


        }

        return $eleves_not_classe;
    }

    /**
     * Displays a form to edit an existing eleve entity.
     *
     * @Route("/{id}/edit", name="eleve_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Eleve $eleve)
    {
        $em = $this->getDoctrine()->getManager();

        $deleteForm = $this->createDeleteForm($eleve);
        $editForm = $this->createForm('AppBundle\Form\EleveType', $eleve);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $eleve->setDateModification(new \DateTime());
            $eleve->setClasse($em->getRepository(Classe::class)->find((int)$request->get('classe')));

           $em->flush();

            return $this->redirectToRoute('eleve_edit', array('id' => $eleve->getId()));
        }

        $censeur=$em->getRepository(Censeur::class)->find($this->getUser()->getId());
        $classes=$em->getRepository(Classe::class)->findBy(array('ecole'=>$censeur->getEcole()->getId()));

        return $this->render('eleve/edit.html.twig', array(
            'eleve' => $eleve,
            'classes' => $classes,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing eleve entity.
     *
     * @Route("/{id}/archive", name="eleve_archive")
     * @Method({"GET", "POST"})
     */
    public function archiveAction(Request $request, Eleve $eleve)
    {
        $em=$this->getDoctrine()->getManager();
        $eleve->setArchiver(1);
        $em->persist($eleve);
        $em->flush();
       return $this->redirectToRoute('eleve_index');
    }
    /**
     * Displays a form to edit an existing eleve entity.
     *
     * @Route("/{id}/desarchive", name="eleve_desarchive")
     * @Method({"GET", "POST"})
     */
    public function desarchiveAction(Request $request, Eleve $eleve)
    {
        $em=$this->getDoctrine()->getManager();
        $eleve->setArchiver(0);
        $em->persist($eleve);
        $em->flush();
        return $this->redirectToRoute('eleve_index');
    }

    /**
     * Deletes a eleve entity.
     *
     * @Route("/{id}", name="eleve_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Eleve $eleve)
    {

        $form = $this->createDeleteForm($eleve);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($eleve);
            $em->flush();
        }

        return $this->redirectToRoute('eleve_index');
    }

    /**
     * Creates a form to delete a eleve entity.
     *
     * @param Eleve $eleve The eleve entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Eleve $eleve)
    {

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('eleve_delete', array('id' => $eleve->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
