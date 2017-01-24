<?php

namespace EscritoresBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use EscritoresBundle\Entity\Users;
use EscritoresBundle\Form\UsersType;
use EscritoresBundle\Form\CambioContrasenaType;

class RegistroController extends Controller
{
    public function registroAction(Request $request){
    	$user = new Users();
        $roles = $this->getDoctrine()->getManager()->getRepository('EscritoresBundle:Roles')->findAll();
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

    public function restableceContrasenaAction(Request $request){
        $form = $this->createForm(CambioContrasenaType::class);
        $form->handleRequest($request);
        $postData = $request->request->all();
        $ip = $request->getClientIp();
        var_dump($ip);
        if($form->isSubmitted() && $form->isValid()){
            $email = $postData['email'];
            $pass = $postData['password'];
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('EscritoresBundle:Users')->findOneBy(array('email'=>$email));

            if (!$user){
                throw $this->createNotFoundException('Email no found'.$email);
            }
            try{
                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);
                $password = $encoder->encodePassword($pass, $user->getSalt(md5(time())));
                $user->setPassword($password);

                $em->flush();
                return $this->redirect($this->generateUrl('login'));
            }
            catch (\Exception $e){
                var_dump($user);
                throw new \Exception($e->getMessage());
            }
        }
        return $this->render('EscritoresBundle:User:contrasena.html.twig',array(
            'request'=>$request,
            'form'=>$form->createView()
        ));
    }
}
