<?php

namespace AppBundle\Controller;

use AppBundle\Entity\EcoleProfesseur;
use AppBundle\Entity\Censeur;
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
        $ecole=$em->getRepository(Censeur::class)->find($this->getUser()->getId())->getEcole();
        $ecole_professeurs = $em->getRepository('AppBundle:EcoleProfesseur')->findBy(['ecole'=> $ecole->getId()]);

        return $this->render('professeur/index.html.twig', array(
            'ecole_professeurs' => $ecole_professeurs,
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
            $id = null;
            $em = $this->getDoctrine()->getManager();
            $ecole_prof = new EcoleProfesseur();
            $data = $form->getData();
            //On verifie si le prof existe deja
            $profexiste = $em->getRepository('AppBundle:Professeur')->findOneBy(['nom'=>$data->getNom(),'prenom'=>$data->getPrenom(),'telephone'=>$data->getTelephone()]);
            if(!$profexiste){
                // le professeur n'existe on le sauvgarde
                $codeprof = strtoupper("PROF-" . substr(sha1(uniqid(mt_rand(), true)), 0, 4));
                $professeur->setCodeProf($codeprof);
                $professeur->setPasswordMobile($this->hash($form->getData()->getPrenom()));
                $professeur->setEmail($form->getData()->getEmail());
                $ecole_prof->setEcole($this->getUser()->getEcole());
                $ecole_prof->setProfesseur($professeur);

                $em->persist($professeur);
                $em->persist($ecole_prof);
                $id  = $professeur->getId();
            }
            else {
                // le professeur existe deja; on lui attribue sa nouvelle ecole

                $ecole_prof->setEcole($this->getUser()->getEcole());
                $ecole_prof->setProfesseur($profexiste);
                $id = $profexiste->getId();
                $em->persist($ecole_prof);
                return $this->redirectToRoute('enseignement_prof_new', array('id' => $id));
            }

            $em->flush();


        }

        return $this->render('professeur/new.html.twig', array(
            'professeur' => $professeur,
            'form' => $form->createView(),
        ));
    }

    public function hash($password){

        $options = [
            'cost' => 13
        ];
        $passwordhash = password_hash($password, PASSWORD_BCRYPT, $options);
        return $passwordhash;
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
        $editForm = $this->createForm('AppBundle\Form\ProfesseurType', $professeur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ecole_professeur_edit', array('id' => $professeur->getId()));
        }

        return $this->render('professeur/edit.html.twig', array(
            'professeur' => $professeur,
            'edit_form' => $editForm->createView(),
           
        ));
    }

    /**
     *  a professeur entity.
     *
     * @Route("archiv/{id}", name="ecole_professeur_archive")
     *  @Method({"GET"})
     */
    public function archiveAction(Request $request, Professeur $professeur)
    {

            $em = $this->getDoctrine()->getManager();
            $ecole=$em->getRepository(Censeur::class)->find($this->getUser()->getId())->getEcole();
            $ecole_prof=$em->getRepository(EcoleProfesseur::class)->findOneBy(array('ecole'=>$ecole->getId(),'professeur'=>$professeur->getId()));
            $ecole_prof->setArchiver(true);
            $em->persist($ecole_prof);
            $em->flush();
        

        return $this->redirectToRoute('ecole_professeur_index');
    }

     /**
     *  a professeur entity.
     *
     * @Route("desarchiv/{id}", name="ecole_professeur_desarchive")
     *  @Method({"GET"})
     */
    public function desarchiveAction(Request $request, Professeur $professeur)
    {

            $em = $this->getDoctrine()->getManager();
            $ecole=$em->getRepository(Censeur::class)->find($this->getUser()->getId())->getEcole();
            $ecole_prof=$em->getRepository(EcoleProfesseur::class)->findOneBy(array('ecole'=>$ecole->getId(),'professeur'=>$professeur->getId()));
            $ecole_prof->setArchiver(false);
            $em->persist($ecole_prof);
            $em->flush();
        

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
