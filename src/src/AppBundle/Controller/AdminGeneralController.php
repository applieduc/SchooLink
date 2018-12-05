<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Donateur controller.
 *
 * @Route("/adminstration")
 */
class AdminGeneralController extends Controller
{
    /**
     * @Route("/", name="adminstration_homepage")
     */
    public function indexAction()
    {
        return $this->render('@App/Administration/index_Admin.html.twig');
    }
}
