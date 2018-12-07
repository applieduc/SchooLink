<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Annee;
use AppBundle\Entity\Censeur;
use AppBundle\Entity\Periode;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
class DashboardController extends Controller
{
    /**
     * @Route("/schools", name="schools")
     */
    public function indexAction(Request $request)
    {

        $em=$this->getDoctrine()->getManager();
        $censeur=$em->getRepository(Censeur::class)->find($this->getUser()->getId());
        $annees=$em->getRepository(Annee::class)->findBy(array('ecole'=>$censeur->getEcole()->getId()),array('id'=>'DESC'));
        $enc=$em->getRepository(Annee::class)->findBy(array('cloture'=>false));
        // replace this example code with whatever you need
        return $this->render('AppBundle:Ecole:index.html.twig',array('annees'=>$annees,'enc'=>$enc));
    }

    /**
     * @Route("/new_school/{id}", name="new_school")
     */
    public function newAction($id)
    {
        $annee=array();

        if($id == 0){
            return $this->render('AppBundle:Ecole:new.html.twig',array('annee'=>$annee));

        }else{
            // replace this example code with whatever you need
            $em=$this->getDoctrine()->getManager();
            $annee=$em->getRepository(Annee::class)->find($id);
            $periodes=$em->getRepository(Periode::class)->findBy(array('annee'=>$id));
            return $this->render('AppBundle:Ecole:edit.html.twig',array('annee'=>$annee,'periodes'=>$periodes));

        }

        
    }
    private function compare_with_now($date){
        return  $date >= new \DateTime();
    }
    private function compare_date($date,$date2){
        return  $date2 > $date;
    }

    /**
     * @Route("/save", name="save")
     */



    public function  saveYearAction(Request $request){
        $em=$this->getDoctrine()->getManager();
        $anne=new Annee();
        $type=$request->get('type');
        $censeur=$em->getRepository(Censeur::class)->find($this->getUser()->getId());

        $anne->setDateDebut(new \DateTime($request->get('debut')));
        if ((int)$type===0){
            $anne->setTypePeriode('Trimestre');
        }else{
            $anne->setTypePeriode('Semestre');
        }
        $anne->setDateFin(new \DateTime($request->get('fin')));
        $anne->setEcole($censeur->getEcole());
        $anne->setCloture(false);
        $em->persist($anne);
        $em->flush();

        $annee=$em->getRepository(Annee::class)->findOneBy(array('dateDebut'=>new \DateTime($request->get('debut'))));
        if ((int)$type===0){
            $periode1=new Periode();
            $periode1->setAnnee($annee);
            $periode1->setDateDebut(new \DateTime($request->get('debutT1')));
            $periode1->setDateFin(new \DateTime($request->get('finT1')));
            $em->persist($periode1);

            $periode2=new Periode();
            $periode2->setAnnee($annee);
            $periode2->setDateDebut(new \DateTime($request->get('debutT2')));
            $periode2->setDateFin(new \DateTime($request->get('finT2')));
            $em->persist($periode2);

            $periode3=new Periode();
            $periode3->setAnnee($annee);
            $periode3->setDateDebut(new \DateTime($request->get('debutT3')));
            $periode3->setDateFin(new \DateTime($request->get('finT3')));
            $em->persist($periode3);
        }else{
            $periode1=new Periode();
            $periode1->setAnnee($annee);
            $periode1->setDateDebut(new \DateTime($request->get('debutS1')));
            $periode1->setDateFin(new \DateTime($request->get('finS1')));
            $em->persist($periode1);

            $periode2=new Periode();
            $periode2->setAnnee($annee);
            $periode2->setDateDebut(new \DateTime($request->get('debutS2')));
            $periode2->setDateFin(new \DateTime($request->get('finS2')));
            $em->persist($periode2);

        }
    $em->flush();
        return $this->redirectToRoute('schools');
    }



    /**
     * @Route("/edit/{id}", name="edit")
     */

    public function  editYearAction(Request $request,$id){
        $em=$this->getDoctrine()->getManager();
        $annee=$em->getRepository(Annee::class)->find($id);
        $annee->setDateDebut(new \DateTime($request->get('debut')));
        $annee->setDateFin(new \DateTime($request->get('fin')));
        $annee->setCloture(false);
        $em->persist($annee);
        $em->flush();

        $type=$annee->getTypePeriode();
        $periodes = $em->getRepository(Periode::class)->findBy(array('annee' => $annee->getId()));

        if ((int)$type==="Trimestre") {
            $i = 1;
            foreach ($periodes as $periode){
                $periode->setDateDebut(new \DateTime($request->get('debutT' . $i)));
            $periode->setDateFin(new \DateTime($request->get('finT' . $i)));
            $em->persist($periode);
            $i++;
        }
        }else{
            $i = 1;
            foreach ($periodes as $periode){
                $periode->setDateDebut(new \DateTime($request->get('debutS' . $i)));
                $periode->setDateFin(new \DateTime($request->get('finS' . $i)));
                $em->persist($periode);
                $i++;
            }
        }
        $em->flush();
        return $this->redirectToRoute('schools');
    }
    /**
     * @Route("/cloture_year/{id}", name="cloture_year")
     */
   public function clotureYearAction($id){
    $em=$this->getDoctrine()->getManager();
        $annee=$em->getRepository(Annee::class)->find($id);
        $annee->setCloture(true);
        $em->persist($annee);
        $em->flush();
        return $this->redirectToRoute('schools');
    }
}
