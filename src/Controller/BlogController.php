<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;

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
     * @Route("/", name="blog_home")
     */
    public function home(): Response
    {
        $posts = $this->repository->findAll();
        return $this->render("blog/home.html.twig", [
            "posts" => $posts
        ]);
    }
}
