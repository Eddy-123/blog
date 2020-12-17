<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PostType;
use App\Uploader\UploaderInterface;
use App\Security\Voter\PostVoter;
use App\Handler\CommentHandler;
use App\Handler\PostHandler;
use Twig\Environment;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class BlogController
{
    /**
     * @var PostRepository
     */
    private $repository;
    
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(
        PostRepository $repository, 
        EntityManagerInterface $manager, 
        Environment $twig
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->twig = $twig;
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
        return new Response($this->twig->render("blog/home.html.twig", [
            "posts" => $posts,
            "pages" => $pages,
            "range" => $range
        ]));
    }
    
    /**
     * @Route("/article/{id}", name="blog_read")
     */
    public function read(
        Post $post, 
        Request $request, 
        CommentHandler $commentHandler,
        UrlGeneratorInterface $urlGenerator
    ): Response {
        $comment = new Comment();
        $comment->setPost($post);
        
        if($this->isGranted("ROLE_USER")){
            $comment->setUser($this->getUser());
        }
        
        $options = [
            "validation_groups" => $this->isGranted("ROLE_USER") ? "Default" : ["Default", "anonymous"]
        ];
        
        if($commentHandler->handle($request, $comment, $options)){
            return new RedirectResponse($urlGenerator->generate("blog_read", [
                "id" => $post->getId()
            ]));
        }

        return new Response($this->twig->render("blog/read.html.twig", [
            "post" => $post,
            "form" => $commentHandler->createView()
        ]));
    }
    
    /**
     * @Route("/article", name="blog_create")
     */
    public function create(
        Request $request, 
        PostHandler $postHandler,
        UrlGeneratorInterface $urlGenerator
    ): Response {
        $this->denyAccessUnlessGranted("ROLE_USER");
        $post = new Post();
        $post->setUser($this->getUser());
        $options = [
            "validation_groups" => ["default", "create"]
        ];
        
        if($postHandler->handle($request, $post, $options)){
            return new RedirectResponse($urlGenerator->generate("blog_read", [
                "id" => $post->getId()
            ]));
        }
            
        return new Response($this->twig->render("blog/create.html.twig", [
            "form" => $postHandler->createView()
        ]));
    }
    
    /**
     * @Route("/modifier/{id}", name="blog_update")
     */
    public function update(
        Post $post, 
        Request $request, 
        PostHandler $postHandler,
        UrlGeneratorInterface $urlGenerator
    ): Response {
        $this->denyAccessUnlessGranted(PostVoter::EDIT, $post);

        if($postHandler->handle($request, $post)){
            return new RedirectResponse($urlGenerator->generate("blog_read", [
                "id" => $post->getId()
            ]));
        }
        
        return new Response($this->twig->render("blog/update.html.twig", [
            "form" => $postHandler->createView()
        ]));
    }
}
