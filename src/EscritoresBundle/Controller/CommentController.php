<?php 

namespace EscritoresBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use EscritoresBundle\Entity\Comment;
use EscritoresBundle\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;

/*
 *Comment Controller
 */
class CommentController extends Controller{

	public function newAction($blog_id){
		$blog = $this->getBlog($blog_id);

		$comment = new Comment();
		$comment->setBlog($blog);
		$form = $this->createForm(CommentType::class, $comment);

		return $this->render('EscritoresBundle:Comment:form.html.twig',array(
			'comment'=>$comment,
			'form'=>$form->createView()
			)
		);
	}

	public function createdAction(Request $request, $blog_id){
		$blog = $this->getBlog($blog_id);

		$comment = new Comment();
		$comment->setBlog($blog);
		$form = $this->createForm(CommentType::class, $comment);
		$form->handleRequest($request);

		if ($form->isValid() && $form->isSubmitted()) {
			 $em = $this->getDoctrine()
                       ->getEntityManager();
            $em->persist($comment);
            $em->flush();

			return $this->redirect($this->generateUrl('escritos_show', array(
	            'id'    => $comment->getBlog()->getId(),
	            'slug'  => $comment->getBlog()->getSlug())) .
	            '#comment-' . $comment->getId()
	        );
		}

		return $this->render('EscritoresBundle:Comment:create.html.twig',array(
			'comment'=>$comment,
			'form'=>$form->createVirew()
			)
		);
	}

	public function getBlog($blog_id){
		$em = $this->getDoctrine()
                    ->getEntityManager();

        $blog = $em->getRepository('EscritoresBundle:Escritos')->find($blog_id);

        if (!$blog) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }

        return $blog;
	}
}