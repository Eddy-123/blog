<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Post;
use App\Entity\Comment;

class PostFixtures extends Fixture {
    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <= 10; $i++){
            $post = new Post();
            $post->setTitle("Titre " . $i);
            $post->setContent("Contenu " . $i);
            $manager->persist($post);
            
            for($j = 1; $j <= 5; $j++){
                $comment = new Comment();
                $comment->setAuthor("Auteur " . $j);
                $comment->setContent("Commentaire " . $j);
                $comment->setPost($post);
                $manager->persist($comment);
            }
        }
        $manager->flush();
    }
}
