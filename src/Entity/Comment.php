<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
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
     * @var ?string
     */
    private $author;    
    
    /**
     * @ORM\Column(type="text")
     * @var ?string
     */
    private $content;
    
    /**
     * @ORM\Column(type="datetime_immutable")
     * @var \DateTimeImmutable
     */
    private $postedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Post::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $post;

    public function __construct() {
        $this->postedAt = new \DateTimeImmutable();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    function getAuthor(): ?string {
        return $this->author;
    }

    function getContent(): ?string {
        return $this->content;
    }

    function getPostedAt(): \DateTimeImmutable {
        return $this->postedAt;
    }

    function setAuthor(string $author): void {
        $this->author = $author;
    }

    function setContent(string $content): void {
        $this->content = $content;
    }

    function setPostedAt(\DateTimeImmutable $postedAt): void {
        $this->postedAt = $postedAt;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

}
