<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotBlank
     * @ORM\Column
     * @var string
     */
    private $title;
    
    /**
     * @Assert\NotBlank
     * @ORM\Column(type="text")
     * @var string
     */
    private $content;
    
    /**
     * @ORM\Column
     * @var string
     */
    private $image;
    
    /**
     * @ORM\Column(type="datetime_immutable")
     * @var \DateTimeImmutable
     */
    private $publishedAt;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="post")
     */
    private $comments;
    
    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @var User
     */
    private $user;
    
    public function __construct() {
        $this->publishedAt = new \DateTimeImmutable();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
    function getTitle(): ?string {
        return $this->title;
    }

    function getContent(): ?string {
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
    
    function getImage(): string {
        return $this->image;
    }

    function setImage(string $image): void {
        $this->image = $image;
    }
    
    function getUser(): User {
        return $this->user;
    }

    function setUser(User $user): void {
        $this->user = $user;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }


}
