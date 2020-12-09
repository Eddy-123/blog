<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(unique=true)
     * @var string
     */
    private $email;
    
    /**
     * @ORM\Column
     * @var string
     */
    private $password;
    
    /**
     * @ORM\Column(unique=true)
     * @var string
     */
    private $pseudo;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @var \DateTimeImmutable
     */
    private $registeredAt;
    
    public function __construct() {
        $this->registeredAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
    function getEmail(): string {
        return $this->email;
    }

    function getPassword(): string {
        return $this->password;
    }

    function getPseudo(): string {
        return $this->pseudo;
    }

    function getRegisteredAt(): \DateTimeImmutable {
        return $this->registeredAt;
    }

    function setEmail(string $email): void {
        $this->email = $email;
    }

    function setPassword(string $password): void {
        $this->password = $password;
    }

    function setPseudo(string $pseudo): void {
        $this->pseudo = $pseudo;
    }

    function setRegisteredAt(\DateTimeImmutable $registeredAt): void {
        $this->registeredAt = $registeredAt;
    }
    
    public function eraseCredentials() {
        
    }
    
    public function getSalt() {
        
    }
    
    public function getRoles() {
        return ['ROLE_USER'];
    }
    
    public function getUsername() {
        return $this->email;
    }

}
