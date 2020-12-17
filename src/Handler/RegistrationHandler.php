<?php

namespace App\Handler;

use App\DataTransferObject\User;
use App\Form\UserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;


class RegistrationHandler extends AbstractHandler{
    
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    protected function getDataTransferObject(): object {
        return new User();
    }

    protected function getFormType(): string {
        return UserType::class;
    }

    protected function process($data): void {
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

}
