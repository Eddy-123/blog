<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Post;
use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends AbstractController
{
    private $repository;
    private $manager;
    public function __construct(
        PostRepository $repository, 
        EntityManagerInterface $manager
    ) {
        $this->manager = $manager;
        $this->repository = $repository;
    }
    /**
     * @Route(
     *      "/{page}/{limit}", 
     *      name="blog_home",  
     *      defaults={"page": 1, "limit": 10}, 
     *      requirements={"page"="\d+"}
     * )
     */
    public function home($page, $limit): Response
    {
        $posts = $this->repository->findPaginatedPosts($page, $limit);
        $pages = ceil($posts->count() / $limit);
        $range = range(max(1, $page - 3), min($page + 3, $pages));
        return $this->render("blog/home.html.twig", [
            "posts" => $posts,
            "pages" => $pages,
            "range" => $range
        ]);
    }
    
    /**
     * @Route("/article/{id}", name="blog_read")
     */
    public function read(Post $post, Request $request): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $comment->setPost($post);
            $this->manager->persist($comment);
            $this->manager->flush();
            return $this->redirectToRoute("blog_read", [
                "id" => $post->getId()
            ]);
        }

        return $this->render("blog/read.html.twig", [
            "post" => $post,
            "form" => $form->createView()
        ]);
    }
}
