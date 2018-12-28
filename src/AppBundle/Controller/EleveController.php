<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Censeur;
use AppBundle\Entity\EleveClasseEcoleAnnee;
use AppBundle\Entity\Classe;
use AppBundle\Entity\Eleve;
use AppBundle\Entity\Annee;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Eleve controller.
 *
 * @Route("eleve")
 */
class EleveController extends Controller
{
    /**
     * Lists all eleve entities.
     *
     * @Route("/", name="eleve_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $censeur=$em->getRepository(Censeur::class)->find($this->getUser()->getId());

        $eleve = new Eleve();
        $form = $this->createForm('AppBundle\Form\EleveType', $eleve);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $eleve->setEcole($censeur->getEcole());
            $em->persist($eleve);
            $em->flush();

            return $this->redirectToRoute('eleve_index');
        }
        $all = $em->getRepository('AppBundle:EleveClasseEcoleAnnee')->findBy(array("ecole"=>$censeur->getEcole()));

        return $this->render('eleve/index.html.twig', array(
            'all' => $all,
            'form' => $form->createView()
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
            $eleve->setDateNaissance(new \Datetime($request->get('dateNaiss')));
           // $all = $em->getRepository('AppBundle:EleveClasseEcoleAnnee')->findBy(array("ecole"=>$censeur->getEcole()));
            $em->persist($eleve);
            $em->flush();


            $ecole=$em->getRepository(Censeur::class)->find($this->getUser()->getId())->getEcole();
            $annee=$em->getRepository(Annee::class)->findOneBy(array('ecole'=>$ecole->getId(),'cloture'=>0));
            $elev=$em->getRepository(Eleve::class)->findOneBy(array('dateCreation' =>$eleve->getDateCreation()));
            $class=$em->getRepository(Classe::class)->find((int)$request->get('classe'));
            $relate=new EleveClasseEcoleAnnee(); 
            $relate->setEcole($censeur->getEcole());
            $relate->setEleve($elev);
            $relate->setClasse($class);
            $relate->setAnnee($annee);
            $em->persist($relate);
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
     * @Route("/{id}", name="eleve_show")
     * @Method("GET")
     */
    public function showAction(Eleve $eleve)
    {
        $deleteForm = $this->createDeleteForm($eleve);
        $em=$this->getDoctrine()->getManager();
        $ecole=$em->getRepository(Censeur::class)->find($this->getUser()->getId())->getEcole();
        $annee=$em->getRepository(Annee::class)->findOneBy(array('ecole'=>$ecole->getId(),'cloture'=>0));
        $relate=$em->getRepository(EleveClasseEcoleAnnee::class)->findOneBy(array('ecole'=>$ecole->getId(),'annee'=>$annee->getId(),'eleve'=>$eleve->getId())); 
     
        return $this->render('eleve/show.html.twig', array(
            'eleve' => $eleve,
            'relate'=>$relate,
            'delete_form' => $deleteForm->createView(),
        ));
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
            $eleve->setPhoto('avatar.png');
            $classe=$em->getRepository(Classe::class)->find((int)$request->get('classe'));
            
            $eleve->setDateNaissance(new \Datetime($request->get('dateNaiss')));
            $em->persist($eleve);
            $em->flush();

            if($request->get('classe')!="0"){
                $ecole=$em->getRepository(Censeur::class)->find($this->getUser()->getId())->getEcole();
                $annee=$em->getRepository(Annee::class)->findOneBy(array('ecole'=>$ecole->getId(),'cloture'=>0));
                $class=$em->getRepository(Classe::class)->find((int)$request->get('classe'));
                $relate=$em->getRepository(EleveClasseEcoleAnnee::class)->findOneBy(array('ecole'=>$ecole->getId(),'annee'=>$annee->getId())); 
                $relate->setClasse($class);
                $em->persist($relate);
                $em->flush();
            }
            
            return $this->redirectToRoute('eleve_index', array('id' => $eleve->getId()));
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

        $ecole=$em->getRepository(Censeur::class)->find($this->getUser()->getId())->getEcole();
        $annee=$em->getRepository(Annee::class)->findOneBy(array('ecole'=>$ecole->getId(),'cloture'=>0));
        $relate=$em->getRepository(EleveClasseEcoleAnnee::class)->findOneBy(array('ecole'=>$ecole->getId(),'annee'=>$annee->getId(),'eleve'=>$eleve->getId())); 
     
        $relate->setArchiver(1);
        $em->persist($relate);
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
        $ecole=$em->getRepository(Censeur::class)->find($this->getUser()->getId())->getEcole();
        $annee=$em->getRepository(Annee::class)->findOneBy(array('ecole'=>$ecole->getId(),'cloture'=>0));
        $relate=$em->getRepository(EleveClasseEcoleAnnee::class)->findOneBy(array('ecole'=>$ecole->getId(),'annee'=>$annee->getId(),'eleve'=>$eleve->getId())); 
     
        $relate->setArchiver(0);
        $em->persist($relate);
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
