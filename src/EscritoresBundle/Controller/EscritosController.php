<?php

namespace EscritoresBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use EscritoresBundle\Entity\Escritos;
use EscritoresBundle\Form\EscritosType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;


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

    public function newAction(Request $request){
        $user = $this->getUser();
        $escrito = new Escritos();
        $form = $this->createForm(EscritosType::class,$escrito);
        $form->handleRequest($request);
        $file=$form['image']->getData();

        if ($form->isValid() && $form->isSubmitted()){
            try{
                $postData = $request->request->all();
                $escrito->setAuthor($user);
                // Recogemos el fichero
                $file=$form['image']->getData();
                //$file = $escrito->getImage()->getData();

                if($file){
                    //Sacamos la extensión del fichero
                    $ext=$file->guessExtension();
                    // Le ponemos un nombre al fichero
                    $file_name=time().".".$ext;
                    // Guardamos el fichero en el directorio uploads que estará en el directorio /web del framework
                    $file->move("uploads", $file_name);
                    // Establecemos el nombre de fichero en el atributo de la entidad
                    $escrito->setImage($file_name);
                    $this->getDoctrine()->getManager()->persist($escrito);
                    $this->getDoctrine()->getManager()->flush();
                    return $this->redirect($this->generateURL('escritores_homepage'));
                }
                else{
                    return $error = FileNotFoundException();
                    var_dump($error);
                }

            }
            catch(\Exception $e){
                throw new \ErrorException("Error Processing Request".$e->getMessage());
            }
        }
        return $this->render('EscritoresBundle:Escritos:new.html.twig',array(
            'form'=>$form->createView(),
            'user'=>$user,
            //'file'=>$file
            )
        );
    }

    public function misescritosAction(Request $request){
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $blog = $em->getRepository('EscritoresBundle:Escritos')->getByEscritor($user);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $blog, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        return $this->render('EscritoresBundle:Escritos:misescritos.html.twig',array(
                'blog'=>$blog,
                'user'=>$user,
                'pagination' => $pagination
            )
        );
    }
}