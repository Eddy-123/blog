<?php

namespace App\DataTransferObject;

use Symfony\Component\Validator\Constraints as Assert;

class Credentials {
    
    /**
     * @Assert\NotBlank
     * @var string|null
     */
    private $username;
    
    /**
     * @Assert\NotBlank
     * @var string|null
     */
    private $password;
    
    function __construct(?string $username = null) {
        $this->username = $username;
    }
    
    function getUsername(): ?string {
        return $this->username;
    }

    function getPassword(): ?string {
        return $this->password;
    }

    function setUsername(string $username): void {
        $this->username = $username;
    }

    function setPassword(string $password): void {
        $this->password = $password;
    }


}
