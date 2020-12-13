<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(nullable=true)
     * @var ?string
     * @Assert\NotBlank(groups={"anonymous"})
     */
    private $author;    
    
    /**
     * @Assert\NotBlank
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

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @var User
     */
    private $user;
    
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

    function getUser(): ?User {
        return $this->user;
    }

    function setUser(User $user): void {
        $this->user = $user;
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
