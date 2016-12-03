<?php
// src/EscritoesBundle/Controller/SecurityController.php;
namespace EscritoresBundle\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\User\UserInterface;
use EscritoresBundle\Entity\Users;

 
class SecurityController extends Controller{
    public function loginAction(Request $request) {
        //Llamamos al servicio de autenticacion
        $authenticationUtils = $this->get('security.authentication_utils');
         
        // conseguir el error del login si falla
        $error = $authenticationUtils->getLastAuthenticationError();
     
        // ultimo nombre de usuario que se ha intentado identificar
        $lastUsername = $authenticationUtils->getLastUsername();
         
        return $this->render('EscritoresBundle:Security:login.html.twig', array(
                'last_username' => $lastUsername,
                'error' => $error,
            ));
        }
    }