<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Censeur;
use AppBundle\Entity\Classe;
use AppBundle\Entity\Eleve;
use AppBundle\Entity\Annee;
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
     * @Route("/{classeType}", name="eleve_type_index")
     * @Method("GET")
     */
    public function indexAction(TypeClasse $classeType)
    {
        $em = $this->getDoctrine()->getManager();
        $eleves=$this->get_eleve($classeType->getClasse());
        $eleve_type=$em->getRepository(EleveTypeClasse::class)->findBy(array('type_classe'=>$classeType->getId()));
        return $this->render('AppBundle:EleveClasse:eleve_index.html.twig', array(
            'eleves_type' => $eleve_type,
            'classeType'=>$classeType,
            'eleves' =>$eleves,

        ));
    }

    /**
     * Creates a new eleve entity.
     *
     * @Route("/save_new_eleve_in_classe/{classeType}", name="save_new_eleve_in_classe")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request,TypeClasse $classeType)
    {
        $em = $this->getDoctrine()->getManager();
        $elve=new EleveTypeClasse();
        $elve->setEleve($em->getRepository(Eleve::class)->find($request->get('eleve')));
        $elve->setTypeClasse($classeType);
        $elve->setArchiver(0);
        $elve->setDateCreation(new \DateTime());
        $elve->setDateModification(new \DateTime());
        $em->persist($elve);
        $em->flush();
        return $this->redirectToRoute('eleve_type_index',array('classeType'=>$classeType->getId()));
    }

    /**
     * Finds and displays a eleve entity.
     *
     * @Route("/add_in_class_type/{classeType}", name="add_in_class_type")
     * @Method("GET")
     */
    public function showAction(TypeClasse $classeType)
    {

        $em=$this->getDoctrine()->getManager();
		$eleves=$this->get_eleve($classeType->getClasse());
        return $this->render('AppBundle:EleveClasse:new_eleve_in_classe.html.twig', array(
            'eleves' =>$eleves,
            'classeType'=>$classeType,

        ));
    }

    private function get_eleve($classe){
        $em = $this->getDoctrine()->getManager();
        $ecole=$em->getRepository(Censeur::class)->find($this->getUser()->getId())->getEcole();
        //$annee=$em->getRepository(Annee::class)->findOneBy(array('ecole'=>$ecole->getId(),'cloture'=>0));
        
        $annee=$this->get('session')->get('annee');
        $eleves = $em->getRepository('AppBundle:EleveClasseEcoleAnnee')->findBy(array('archiver' =>false,'classe'=>$classe,'ecole'=>$this->getUser()->getEcole()->getId(),'annee'=>$annee));
        $ecole=$em->getRepository(Censeur::class)->find($this->getUser()->getId())->getEcole();


        $eleves_not_classe=array();
        $i=0;
        foreach ($eleves as $val) {
            $eleve = $em->getRepository('AppBundle:EleveTypeClasse')->findOneBy(array('eleve' =>$val->getEleve()->getId(),'archiver'=>false));
            if($eleve!=null ){
                    $eleves_not_classe[$i]=$val->getEleve();
                    $i++;


            }elseif ($eleve==null){
                $eleves_not_classe[$i]=$val->getEleve();
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
           // $eleve->setClasse($em->getRepository(Classe::class)->find((int)$request->get('classe')));

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
     * @Route("/remove_eleve_in_classeType/{id}", name="remove_eleve_in_classeType")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $em=$this->getDoctrine()->getManager();
        $elct=$em->getRepository(EleveTypeClasse::class)->find((int)$id);
        $type=$elct->getTypeClasse();
        $em->remove($elct);
        $em->flush();

        return $this->redirectToRoute('eleve_type_index',array('classeType'=>$type->getId()));
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
