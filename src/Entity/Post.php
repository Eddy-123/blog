<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    /**
     * @var int|null
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column
     * @var string
     */
    private $title;
    
    /**
     * @ORM\Column(type="text")
     * @var string
     */
    private $content;
    
    /**
     * @ORM\Column(type="datetime_immutable")
     * @var \DateTimeImmutable
     */
    private $publishedAt;
    
    public function __construct() {
        $this->publishedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
    function getTitle(): string {
        return $this->title;
    }

    function getContent(): string {
        return $this->content;
    }

    function getPublishedAt(): \DateTimeImmutable {
        return $this->publishedAt;
    }

    function setTitle(string $title): void {
        $this->title = $title;
    }

    function setContent(string $content): void {
        $this->content = $content;
    }

    function setPublishedAt(\DateTimeImmutable $publishedAt): void {
        $this->publishedAt = $publishedAt;
    }


}
