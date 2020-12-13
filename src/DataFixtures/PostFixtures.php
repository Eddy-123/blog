<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Post;
use App\Entity\Comment;
use App\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PostFixtures extends Fixture implements DependentFixtureInterface {
    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <= 10; $i++){
            $post = new Post();
            $post->setTitle("Titre " . $i);
            $post->setContent("Contenu " . $i);
            $post->setUser($this->getReference(sprintf("user-%d", $i)));
            $post->setImage(sprintf("https://picsum.photos/seed/%d/400/300", $i));
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
    
    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}
