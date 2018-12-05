<?php

namespace AppBundle\Controller;


<<<<<<< HEAD
use AppBundle\Entity\AdminGen;
=======
>>>>>>> vR1'
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
<<<<<<< HEAD
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
=======
>>>>>>> vR1'
        return $this->render('@App/Administration/index_Admin.html.twig');
    }
}
