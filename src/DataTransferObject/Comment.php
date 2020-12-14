<?php

namespace App\DataTransferObject;

use Symfony\Component\Validator\Constraints as Assert;

class Comment {
    /**
     * @Assert\NotBlank(groups={"anonymous"})
     * @Assert\Length(min=2, groups={"anonymous"})
     * @var string|null
     */
    private $author;
    
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=5)
     * @var string|null
     */
    private $content;

    public function getAuthor(): ?string {
        return $this->author;
    }

    public function getContent(): ?string {
        return $this->content;
    }

    public function setAuthor(?string $author): void {
        $this->author = $author;
    }

    public function setContent(?string $content): void {
        $this->content = $content;
    }


}
