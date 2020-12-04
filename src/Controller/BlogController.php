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
     * @Route(
     *      "/{page}/{limit}", 
     *      name="blog_home",  
     *      defaults={"page": 1, "limit": 2}
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
}
