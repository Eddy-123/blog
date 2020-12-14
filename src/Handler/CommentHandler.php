<?php

namespace App\Handler;

use App\Handler\AbstractHandler;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;


class CommentHandler extends AbstractHandler{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    
    protected function getFormType(): string {
        return CommentType::class;
    }
    
    protected function process($data): void {
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }
}
