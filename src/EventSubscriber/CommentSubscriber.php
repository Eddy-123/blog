<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Event\ReverseEvent;
use App\Event\TransferEvent;
use Symfony\Component\Security\Core\Security;
use App\Entity\Comment;

class CommentSubscriber implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security) {
        $this->security = $security;
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
        if(!$event->getOriginalData() instanceof Comment){
            return;
        }
        $event->getData()->setAuthor($event->getOriginalData()->getAuthor());
        $event->getData()->setContent($event->getOriginalData()->getContent());
    }
    
    public function onReverse(ReverseEvent $event): void 
    {
        if(!$event->getOriginalData() instanceof Comment){
            return;
        }
        if($this->security->isGranted("ROLE_USER")){
            $event->getOriginalData()->setUser($this->security->getUser());
        }
        $event->getOriginalData()->setAuthor($event->getData()->getAuthor());
        $event->getOriginalData()->setContent($event->getData()->getContent());
    }
}
