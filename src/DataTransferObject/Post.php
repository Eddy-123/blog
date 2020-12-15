<?php

namespace App\DataTransferObject;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class Post {
    
    /**
     * @var string|null
     * @Assert\NotBlank
     */
    private $title;
    
    /**
     * @var string|null
     * @Assert\NotBlank
     * @Assert\Length(min=10)
     */
    private $content;
    
    /**
     * @var UploadedFile|null
     * @Assert\Image
     * @Assert\NotBlank(groups={"create"})
     */
    private $image;
    
    public function getTitle(): ?string {
        return $this->title;
    }

    public function getContent(): ?string {
        return $this->content;
    }

    public function getImage(): ?UploadedFile {
        return $this->image;
    }

    public function setTitle(?string $title): void {
        $this->title = $title;
    }

    public function setContent(?string $content): void {
        $this->content = $content;
    }

    public function setImage(?UploadedFile $image): void {
        $this->image = $image;
    }


}
