<?php

namespace EscritoresBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use EscritoresBundle\Entity\Escritos;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{
    public function indexAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $blog = $em->getRepository('EscritoresBundle:Escritos')->getLatesEscritos();
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $blog, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );
        
        return $this->render('EscritoresBundle:Default:index.html.twig',array(
        	'blog'=>$blog,
            'pagination' => $pagination
        	)
        );
    }

    public function showAction($id,$slug){
    	$em = $this->getDoctrine()->getManager();

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
        $em = $this->getDoctrine()->getManager();
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

    public function aboutusAction(){
        return $this->render('EscritoresBundle:Default:about.html.twig');
    }
}
