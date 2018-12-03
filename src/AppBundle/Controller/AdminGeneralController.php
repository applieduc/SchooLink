<?php

namespace AppBundle\Controller;


use AppBundle\Entity\AdminGen;
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
//        $admin=new AdminGen();
//        $admin->setNom('Admin');
//        $admin->setPrenom('admin');
//        $admin->setUsername('admin');
//        $admin->setAdresse('');
//        $admin->setTelephone('111111');
//        $admin->setEmail('admin@gmail.com');
//        $admin->setPassword('1234567');
//        $admin->setRoles(array('ROLE_ADMIN_GENERAL'));
//        $this->getDoctrine()->getManager()->persist($admin);
//        $this->getDoctrine()->getManager()->flush();
        return $this->render('@App/Administration/index_Admin.html.twig');
    }
}
