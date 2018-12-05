<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Donateur controller.
 *
 * @Route("/ecole")
 */

class EtablisementController extends Controller
{
    /**
     * @Route("/", name="ecole_homepage")
     */
    public function indexAction()
    {
        //return $this->render('@App/Etablissement/index_Ecole.html.twig');
    }

}
