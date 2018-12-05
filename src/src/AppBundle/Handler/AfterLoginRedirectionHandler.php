<?php
namespace AppBundle\Handler;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use FOS\UserBundle\Event\GetResponseUserEvent;

class AfterLoginRedirectionHandler implements AuthenticationSuccessHandlerInterface {

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    private $event;

    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router, Session $session)
    {
        $this->router = $router;
        $this->session = $session;
        //$this->event = $event;
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @return RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {

        // On regarde la date de connexion
//        $dateLastConnexion = $token->getUser()->getLastLogin();
//        $createdAt = $token->getUser()->getCreatedAt()->modify('+1 hour');

        // Compute date for test if first login is correct
//        if($dateLastConnexion < $createdAt){
//            $redirection  = new RedirectResponse($this->router->generate('fos_user_security_logout'));
//            return $redirection;
//        }

        // On récupère la liste des rôles d'un utilisateur
        $roles = $token->getRoles();
//        // On transforme le tableau d'instance en tableau simple
        $rolesTab = array_map(function($role){
            return $role->getRole();
        }, $roles);

        $user = $token->getUser()->getusername();
//        $section = $token->getUser()->getSection();
        $msg = "";
       $session = $request->getSession();
//        $session->set('section_id',$section);

        if(in_array('ROLE_ADMIN_GENERAL', $rolesTab, true)){

            $msg = "Vous êtes connecté en tant que Administrateur General";

            $redirection  = new RedirectResponse($this->router->generate('adminstration_homepage'));
          //  $this->event->setResponse($redirection);

          //  $msg = "Vous êtes connecté en tant service commercial";
            $this->session->getFlashBag()->add('info', "Bienvenu $user. $msg.");

        }elseif(in_array('ROLE_CENSEUR', $rolesTab, true)){
            $msg = "Vous êtes connecté en tant que censeur";
            $redirection  = new RedirectResponse($this->router->generate('homepage_admin'));

        }elseif(in_array('ROLE_INVESTISSEUR', $rolesTab, true)){


            $msg = "Vous êtes connecté en tant qu'investisseur";
            $redirection  = new RedirectResponse($this->router->generate('donateur_admin_TB'));


        }
        elseif(in_array('ROLE_COMMERCIAL', $rolesTab, true)){
//            var_dump($session->get('section_id'));
//            die();
            $redirection  = new RedirectResponse($this->router->generate('sc_admin'));
            $msg = "Vous êtes connecté en tant service commercial";
            $this->session->getFlashBag()->add('info', "Bienvenu $user. $msg.");


        }

        $this->session->getFlashBag()->add('info', "Bienvenu $user. $msg.");



        return $redirection;
    }
}