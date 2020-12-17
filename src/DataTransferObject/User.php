<?php

namespace App\DataTransferObject;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\{
    UniqueEmail,
    UniquePseudo
};

class User {
    
    /**
     * @var string|null
     * @Assert\Email
     * @Assert\NotBlank
     * @UniqueEmail
     */
    private $email;
    
    /**
     * @var string|null
     * @Assert\NotBlank
     * @UniquePseudo
     */
    private $pseudo;
    
    /**
     * @var string|null
     * @Assert\NotBlank
     * @Assert\Length(min=8)
     */
    private $password;
    
    public function getEmail(): ?string {
        return $this->email;
    }

    public function getPseudo(): ?string {
        return $this->pseudo;
    }

    public function getPassword(): ?string {
        return $this->password;
    }

    public function setEmail(?string $email): void {
        $this->email = $email;
    }

    public function setPseudo(?string $pseudo): void {
        $this->pseudo = $pseudo;
    }

    public function setPassword(?string $password): void {
        $this->password = $password;
    }


}
