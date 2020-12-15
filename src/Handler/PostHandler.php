<?php

namespace App\Handler;

use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;
use App\DataTransferObject\Post;

class PostHandler extends AbstractHandler {
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    protected function getFormType(): string {
        return PostType::class;
    }

    protected function process($data): void {
        if($this->entityManager->getUnitOfWork()->getEntityState($data) === UnitOfWork::STATE_NEW)
        {
            $this->entityManager->persist($data);
        }
        $this->entityManager->flush();
    }

    protected function getDataTransferObject(): object {
        return new Post();
    }

}
