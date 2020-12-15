<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Event\ReverseEvent;
use App\Event\TransferEvent;
use Symfony\Component\Security\Core\Security;
use App\Uploader\UploaderInterface;
use App\Entity\Post;


class PostSubscriber implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    private $security;
    
    /**
     * @var UploaderInterface 
     */
    private $uploader;

    public function __construct(Security $security, UploaderInterface $uploader) {
        $this->security = $security;
        $this->uploader = $uploader;
    }

    public static function getSubscribedEvents()
    {
        return [
            TransferEvent::NAME => "onTransfer",
            ReverseEvent::NAME => "onReverse"
        ];
    }
    
    /**
     * @param TransferEvent $event
     * @return void
     */
    public function onTransfer(TransferEvent $event): void 
    {
        if(!$event->getOriginalData() instanceof Post){
            return;
        }
        
        $event->getData()->setTitle($event->getOriginalData()->getTitle());
        $event->getData()->setContent($event->getOriginalData()->getContent());
    }
    
    public function onReverse(ReverseEvent $event): void 
    {
        if(!$event->getOriginalData() instanceof Post){
            return;
        }
        
        if($event->getData()->getImage() !== null){
            $event->getOriginalData()->setImage($this->uploader->upload($event->getData()->getImage()));
        }
                
        $event->getOriginalData()->setUser($this->security->getUser());
        $event->getOriginalData()->setTitle($event->getData()->getTitle());
        $event->getOriginalData()->setContent($event->getData()->getContent());
    }
}
