<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ClasseMatiere;
use AppBundle\Entity\Classe;
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
     * @Route("/{id}", name="class_mat_index")
     * @Method("GET")
     */
    public function indexAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();

        $classeMatieres = $em->getRepository('AppBundle:ClasseMatiere')->findBy(array('classe'=>$id));



        $classeMatiere = new ClasseMatiere();
        $form = $this->createForm('AppBundle\Form\ClasseMatiereType', $classeMatiere);
        $form->handleRequest($request);
        $classe= $em->getRepository(Classe::class)->find($id);
        if ($form->isSubmitted() && $form->isValid()) {
           
            $em = $this->getDoctrine()->getManager();
            $classe= $em->getRepository(Classe::class)->find($id);
            $classeMatiere->setClasse($classe);
             $verify=$em->getRepository(ClasseMatiere::class)->findOneBy(array('classe'=>$classe->getId(),'matiere'=>$classeMatiere->getMatiere()->getId()));
             if($verify==null){
                $em->persist($classeMatiere);
                $em->flush();
            }
           
            
            return $this->redirectToRoute('class_mat_index', array('id' =>$id ));
        }
        
        return $this->render('classematiere/index.html.twig', array(
            'classeMatieres' => $classeMatieres,
            'classe'=>$classe,
            'classeMatiere' => $classeMatiere,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new classeMatiere entity.
     *
     * @Route("/new/{id}", name="class_mat_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request,$id)
    {
        $classeMatiere = new ClasseMatiere();
        $form = $this->createForm('AppBundle\Form\ClasseMatiereType', $classeMatiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $em = $this->getDoctrine()->getManager();
            $classe= $em->getRepository(Classe::class)->find($id);
            $classeMatiere->setClasse($classe);
             $verify=$em->getRepository(ClasseMatiere::class)->findOneBy(array('classe'=>$classe->getId(),'matiere'=>$classeMatiere->getMatiere()->getId()));
             if($verify==null){
                $em->persist($classeMatiere);
                $em->flush();
            }
           
            
            return $this->redirectToRoute('class_mat_show', array('id' => $classeMatiere->getId(),'cl'=>$id));
        }
        
        return $this->render('classematiere/new.html.twig', array(
            'classeMatiere' => $classeMatiere,
            'form' => $form->createView(),
            'classe_id'=>$id
        ));
    }

    /**
     * $cl===$classe_id
     * Finds and displays a classeMatiere entity.
     *
     * @Route("/{id}/{cl}", name="class_mat_show")
     * @Method("GET")
     */
    public function showAction(ClasseMatiere $classeMatiere,$cl)
    {
        $deleteForm = $this->createDeleteForm($classeMatiere);

        return $this->render('classematiere/show.html.twig', array(
            'classeMatiere' => $classeMatiere,
            'delete_form' => $deleteForm->createView(),
            'classe_id'=>$cl
        ));
    }

    /**
     * $cl===$classe_id
     * Displays a form to edit an existing classeMatiere entity.
     *
     * @Route("/{id}/edit/{cl}", name="class_mat_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ClasseMatiere $classeMatiere,$cl)
    {
        $deleteForm = $this->createDeleteForm($classeMatiere);
        $editForm = $this->createForm('AppBundle\Form\ClasseMatiereType', $classeMatiere);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('class_mat_edit', array('id' => $classeMatiere->getId(), 'cl'=>$cl));
        }

        return $this->render('classematiere/edit.html.twig', array(
            'classeMatiere' => $classeMatiere,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'classe_id'=>$cl
        ));
    }
 /**
     * Displays a form to edit an existing classeMatiere entity.
     *
     * @Route("/{id}/archive/{cl}", name="class_mat_archive")
     * @Method({"GET", "POST"})
     */
    public function archiveAction(Request $request, ClasseMatiere $classeMatiere,$cl)
    {
        $em=$this->getDoctrine()->getManager();
        $classeMatiere->setArchiver(1);
        $em->persist($classeMatiere);
        $em->flush();
        return $this->redirectToRoute('class_mat_index',array('id'=>$cl));
    }
    /**
     * Displays a form to edit an existing classeMatiere entity.
     *
     * @Route("/{id}/desarchive/{cl}", name="class_mat_desarchive")
     * @Method({"GET", "POST"})
     */
    public function desarchiveAction(Request $request, ClasseMatiere $classeMatiere,$cl)
    {
        $em=$this->getDoctrine()->getManager();
        $classeMatiere->setArchiver(0);
        $em->persist($classeMatiere);
        $em->flush();
        return $this->redirectToRoute('class_mat_index',array('id'=>$cl));
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
