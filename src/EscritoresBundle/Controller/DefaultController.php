<?php

namespace EscritoresBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use EscritoresBundle\Entity\Escritos;
class DefaultController extends Controller
{
    public function indexAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $blog = $em->getRepository('EscritoresBundle:Escritos')->getLatesEscritos();
        
        return $this->render('EscritoresBundle:Default:index.html.twig',array(
        	'blog'=>$blog
        	)
        );
    }

    public function showAction($id,$slug){
    	$em = $this->getDoctrine()->getEntityManager();

    	$blog = $em->getRepository('EscritoresBundle:Escritos')->find($id);
    	if(!$blog){
    		throw $this->createNotfoundException('Unable to find the post');
    	}

    	$comments = $em->getRepository('EscritoresBundle:Comment')
    					->getCommentsForBlog($blog->getId());

    	return $this->render('EscritoresBundle:Escritos:show.html.twig', array(
    		'blog'=>$blog,
    		'comments'=>$comments,
    		)
    	);
    }

    public function sidebarAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $tags = $em->getRepository('EscritoresBundle:Escritos')->getTags();
        $tagWeights = $em->getRepository('EscritoresBundle:Escritos')->getTagsWeights($tags);

        $commentLimit = $this->container->getParameter('blogger_blog.comments.latest_comment_limit');
        //$commentLimit = $this->container->getParameter('blogger_blog.comments.latest_comment_limit');
        $latesComments = $em->getRepository('EscritoresBundle:Comment')->getLastesComments($commentLimit);

        return $this->render('EscritoresBundle:Comunes:sidebar.html.twig',array(
            'tags' => $tagWeights,
            'latesComments'=> $latesComments
            )
        );
    }
}
