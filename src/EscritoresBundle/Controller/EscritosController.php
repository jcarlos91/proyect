<?php

namespace EscritoresBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use EscritoresBundle\Entity\Escritos;
use EscritoresBundle\Form\EscritosType

class EscritosController extends Controller{
	public function showAction($id,$slug){
		$em = $this->getDoctrine()->getManager();
		$blog = $em->getRepository('EscritoresBundle:Escritos')->find($id);
		if(!$blog){
			throw $this->createNotFoundException('Unable to find the post');
		}

		$comment = $em->getRepository('EscritoresBundle:Comment')
						->getCommentsForBlog($blog->getId());

		return $this->render('EscritoresBundle:Escritos:show.html.twig',array(
			'blog'=>$blog,
			'comment'=>$comment
			)
		);
	}

    /**
     * @param Resquest $request
     * @throws \ErrorException
     */
    public function newAction(Resquest $request){

        $form = $this->createForm(EscritosType::class);
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()){
            try{

            }
            catch(\Exception $e){
                throw new \ErrorException($e->getMessage());
            }
        }
        return $this->render();
    }
}