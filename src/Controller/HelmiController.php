<?php

namespace App\Controller;

use DateTime;
use DateTimeImmutable;



use App\Entity\Article;

use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;

use Symfony\Component\Form\Form;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;

class HelmiController extends AbstractController
{
    /**
     * @Route("/helmi", name="helmi")
     */
    public function index(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request )
    {
        
        $articles = $paginator->paginate($articleRepository->findAll(),
        $request->query->getInt('page',1),3);
        return $this->render('helmi/index.html.twig', [
            'controller_name' => 'HelmiController',
            'articles' => $articles
        ]);
    }
    /**
     * @Route("/", name="home")
     */
    public function home(){
        return $this->render('helmi/home.html.twig');
    }
    

    /**
     * @Route("/helmi/new", name="helmi_create")
     */
    public function create(Request $request){
    
    $article = new Article();
    $form = $this->createform(ArticleType::class,$article);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) { 
     $article->setCreatedAt(new DateTimeImmutable());
     $article->setImage("http://placehold.it/350x150");   
     $entityManager = $this->getDoctrine()->getManager();
     $entityManager->persist($article);
     $entityManager->flush();

    return $this->redirectToRoute("helmi_show",["id"=>$article->getId()]);
        

    }

    

    
        return $this->render('helmi/create.html.twig',['form' => $form->createView()]);
        
    }
    /**  
     * @Route("helmi/{id}/edit", name="helmi_edit")
     */
    public function edit(Article $article,Request $request):Response
    {

    
        
        $form = $this->createform(ArticleType::class,$article);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
           
         $entityManager = $this->getDoctrine()->getManager();
         $entityManager->persist($article);
         $entityManager->flush();
    
        return $this->redirectToRoute("helmi_show",["id"=>$article->getId()]);
        }  
        
        return $this->render('helmi/edit.html.twig',['editform' => $form->createView()]);
    }
    

   
    /**
     * @Route("helmi/{id}", name="helmi_show")
     */
    public function show(Article $article,Request $request){
    
        $comment = new Comment();
    $form = $this->createform(CommentType::class,$comment);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) { 
     $comment->setCreatedAt(new DateTimeImmutable());
     $comment->setArticle($article);   
     $entityManager = $this->getDoctrine()->getManager();
     $entityManager->persist($comment);
     $entityManager->flush();
    } 
        return $this->render('helmi/show.html.twig', ['article' => $article,'commentform' => $form->createView()]);
    
    
}
    
     


}
