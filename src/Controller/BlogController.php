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
    public function read(
        Post $post, 
        Request $request, 
        CommentHandler $commentHandler
    ): Response {
        $comment = new Comment();
        $comment->setPost($post);
        
        if($this->isGranted("ROLE_USER")){
            $comment->setUser($this->getUser());
        }
        
        $form = $this->createForm(CommentType::class, $comment, [
            "validation_groups" => $this->isGranted("ROLE_USER") ? "Default" : ["Default", "anonymous"]
        ]);
        $form->handleRequest($request);
        
        $options = [
            "validation_groups" => $this->isGranted("ROLE_USER") ? "Default" : ["Default", "anonymous"]
        ];
        
        if($commentHandler->handle($request, $comment, $options)){
            return $this->redirectToRoute("blog_read", [
                "id" => $post->getId()
            ]);
        }

        return $this->render("blog/read.html.twig", [
            "post" => $post,
            "form" => $form->createView()
        ]);
    }
    
    /**
     * @Route("/article", name="blog_create")
     */
    public function create(
        Request $request, 
        UploaderInterface $uploader
    ): Response {
        $this->denyAccessUnlessGranted("ROLE_USER");
        $post = new Post();
        $post->setUser($this->getUser());
        $form = $this->createForm(PostType::class, $post, [
            "validation_groups" => ["default", "create"]
        ]);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            /** @var UploadedFile $file */
            $file = $form->get("file")->getData();
            $post->setImage($uploader->upload($file));
            $this->manager->persist($post);
            $this->manager->flush();
            return $this->redirectToRoute("blog_read", [
                "id" => $post->getId()
            ]);
        }
            
        return $this->render("blog/create.html.twig", [
            "form" => $form->createView()
        ]);
    }
    
    /**
     * @Route("/modifier/{id}", name="blog_update")
     */
    public function update(
        Post $post, 
        Request $request, 
        UploaderInterface $uploader
    ): Response {
        $this->denyAccessUnlessGranted(PostVoter::EDIT, $post);
        
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            /** @var UploadedFile $file */
            $file = $form->get("file")->getData();
            if($file !== null){
                $post->setImage($uploader->upload($file));
            }            
            $this->manager->flush();
            return $this->redirectToRoute("blog_read", [
                "id" => $post->getId()
            ]);
        }
        
        return $this->render("blog/update.html.twig", [
            "form" => $form->createView()
        ]);
    }
}
