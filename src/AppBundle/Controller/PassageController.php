<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Annee;
use AppBundle\Entity\Censeur;
use AppBundle\Entity\Classe;
use AppBundle\Entity\ClasseMatiere;
use AppBundle\Entity\ClasseMatiereProfesseurAnnee;
use AppBundle\Entity\Ecole;
use AppBundle\Entity\Eleve;
use AppBundle\Entity\EleveClasseEcoleAnnee;
use AppBundle\Entity\EleveTypeClasse;
use AppBundle\Entity\Note;
use AppBundle\Entity\Periode;
use AppBundle\Entity\Professeur;
use AppBundle\Entity\Resultat;
use AppBundle\Entity\TypeClasse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Passage controller.
 *
 * @Route("Passage")
 */
class PassageController extends Controller
{
    /**
     * Lists all eleve entities.
     *
     * @Route("/", name="passage_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $ecole=$this->getUser()->getEcole();
    //    $annee=$em->getRepository(Annee::class)->findBy(array('ecole'=>$ecole->getId(),'cloture'=>0));
        $annee=$this->get('session')->get('annee');
        $periode=$em->getRepository(Periode::class)->findBy(array('annee'=>$annee->getId()));

        $classes=$em->getRepository(Classe::class)->findBy(array('ecole'=>$ecole->getId()));

        $tabClasse=$this->getClasseEcole($em,$classes);

        $param_type=1;
        if ($request->get('type') !="") $param_type=$request->get('type');
        $classprof=$em->getRepository(ClasseMatiereProfesseurAnnee::class)->findBy(array('type_classe'=>$param_type));

        $param_periode=1;
        if ($request->get('periode') !="") $param_periode=(int)$request->get('periode');

        $param_classe=0;
        if ($request->get('classe') !="")$param_classe=$request->get('classe');

        $tab=$em->getRepository(Resultat::class)->findBy(array('annee'=>$annee->getId(),'type_classe'=>$param_type));
        return $this->render('AppBundle:Bulletin:passage.html.twig', array(
            'tab'=>$tab,
            'param_type'=>$param_type,
            'param_periode'=>$param_periode,
            'tabClasse'=>$tabClasse,
            'param_classe'=>$param_classe,
            'periode'=>$periode,
            'allClasses'=>$classes,

        ));
    }
    private function getClasseEcole($em,$classes)
    {
        $tabClasse=array();
        for ($i=0; $i<sizeof($classes);$i++)
        {
            $type=$em->getRepository(TypeClasse::class)->findBy(array('classe'=>$classes[$i]->getId()));
            for($j=0; $j<sizeof($type);$j++)
            {
                $tabClasse[$i][$j]['classe']=$classes[$i]->getLibelle()." ".$type[$j]->getLibelle();
                $tabClasse[$i][$j]['type']=$type[$j]->getId();
                $tabClasse[$i][$j]['number']=$i;
            }
        }

        return $tabClasse;
    }

    /**
     * Lists all eleve entities.
     *
     * @Route("/passage", name="passage_action")
     * @Method("GET")
     */
    public function passage(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $ecole=$this->getUser()->getEcole();
        $annee=$this->get('session')->get('annee');
       //$periode=$em->getRepository(Periode::class)->findBy(array('annee'=>$annee[0]->getId()));

           $tab=$em->getRepository(Resultat::class)->findBy(array('annee'=>$annee->getId(),'type_classe'=>$request->get('type')));
        for ($i=0;$i<sizeof($tab);$i++)
        {
            $tab[$i]->setActif(false);
            $el=$em->getRepository(EleveClasseEcoleAnnee::class)->findBy(array('eleve'=>$tab[$i]->getEleve()->getId(), 'annee'=>$annee->getId()));
            $el[0]->setClasse($em->getRepository(Classe::class)->find($request->get('classe_passe')));
            $em->persist($el[0]);
            $em->persist($tab[$i]);
            $em->flush();
        }




        return $this->redirectToRoute('passage_index');
    }


}
