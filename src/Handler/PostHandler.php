<?php

namespace App\Handler;

use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use App\Uploader\UploaderInterface;
use Doctrine\ORM\UnitOfWork;

class PostHandler extends AbstractHandler {
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    
    /**
     * @var UploaderInterface
     */
    private $uploader;

    public function __construct(EntityManagerInterface $entityManager, UploaderInterface $uploader) {
        $this->entityManager = $entityManager;
        $this->uploader = $uploader;
    }

    protected function getFormType(): string {
        return PostType::class;
    }

    protected function process($data): void {
        /** UploadedFile */
        $file = $this->form->get("file")->getData();
        
        if($file !== null){
            $data->setImage($this->uploader->upload($file));
        }
                
        if($this->entityManager->getUnitOfWork()->getEntityState($data) === UnitOfWork::STATE_NEW)
        {
            $this->entityManager->persist($data);
        }
        $this->entityManager->flush();
    }

    protected function getDataTransferObject(): object {
        
    }

}
