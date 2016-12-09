<?php

namespace EscritoresBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use EscritoresBundle\Entity\Escritos;

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
}