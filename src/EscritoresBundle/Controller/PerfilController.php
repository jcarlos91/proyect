<?php

namespace EscritoresBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use EscritoresBundle\Entity\Perfil;
use EscritoresBundle\Form\PerfilType;
use Symfony\Component\HttpFoundation\Request;
use EscritoresBundle\Entity\Users;
/*
 *Comment Controller
 */
class PerfilController extends Controller{
	public function perfilAction(Request $request){
		//$user = $this->get('security.token_storage')->getToken()->getUsername();
        $user = $this->getUser();
        $repository = $this->getDoctrine()->getRepository("EscritoresBundle:Users");
        
		$perfil = new Perfil();
    	$form = $this->createForm(PerfilType::class, $perfil);
    	$form->handleRequest($request);
    	if($form->isSubmitted() && $form->isValid()){
            try {
	            	$postData = $request->request->all();
                    $perfil->setIdUser($user);
					$this->getDoctrine()->getManager()->persist($perfil);
					$this->getDoctrine()->getManager()->flush();
	                return $this->redirect($this->generateURL('escritos_new'));
            } catch (\Exception $e) {
                throw new \Exception("Error Processing Request".$e->getMessage());              
            }
        }
    	return $this->render('EscritoresBundle:Perfil:nuevo.html.twig',array(
    			'form'=>$form->createView(),
    			'user'=>$user
    		)
    	);

	}
}
