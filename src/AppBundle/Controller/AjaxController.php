<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Ajax Controller
 *
 * @Route("ajax")
 */
class AjaxController extends Controller
{
    /**
     *
     * @Route("/matiere-classe/{id}",name="matiere-classe",options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function listMatiereClasse($id)
    {
        // Get Entity manager and repository
        $em = $this->getDoctrine()->getManager();
        $matieres = $em->getRepository("AppBundle:ClasseMatiere")->findBy(['classe'=>$id,'archiver'=>false]);

        $responseArray = array();
        foreach($matieres as $matiere){
            $responseArray[] = array(
                "id" => $matiere->getId(),
                "libelle" => $matiere->getMatiere()->getLibelle()
            );
        }


        return new JsonResponse($responseArray);


    }
}
