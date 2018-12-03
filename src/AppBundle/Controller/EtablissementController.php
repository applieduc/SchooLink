<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
/**
 * Donateur controller.
 *
 * @Route("/ecole")
 */
class EtablissementController extends Controller
{
    /**
     * @Route("/", name="ecole_homepage")
     */
    public function indexAction()
    {
        return $this->render('');
    }
}
