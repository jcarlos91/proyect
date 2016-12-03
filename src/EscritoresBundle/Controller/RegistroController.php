<?php

namespace EscritoresBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use EscritoresBundle\Entity\Users;
use EscritoresBundle\Form\UsersType;
class RegistroController extends Controller
{
    public function registroAction(Request $request){
    	$user = new Users();
    	$form = $this->createForm(UsersType::class, $user);
    	$form->handleRequest($request);
    	if($form->isSubmitted() && $form->isValid()){
            try {
            	$postData = $request->request->all();
            	$pass = $postData['pass'];
            	$factory = $this->get('security.encoder_factory');
            	$encoder = $factory->getEncoder($user);
				$password = $encoder->encodePassword($pass, $user->getSalt(md5(time())));
				$user->setPassword($password);
                $this->getDoctrine()->getManager()->persist($user);
                $this->getDoctrine()->getManager()->flush();
                return $this->redirect($this->generateURL('login'));
            } catch (\Exception $e) {
                throw new \Exception("Error Processing Request".$e->getMessage());              
            }
        }
    	return $this->render('EscritoresBundle:User:nuevo.html.twig',array(
    			'form'=>$form->createView()
    		)
    	);
    }
}
