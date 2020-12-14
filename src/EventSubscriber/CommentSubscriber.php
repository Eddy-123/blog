<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Event\ReverseEvent;
use App\Event\TransferEvent;
use Symfony\Component\Security\Core\Security;


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
        $event->getData()->setAuthor($event->getOriginalData()->getAuthor());
        $event->getData()->setContent($event->getOriginalData()->getContent());
    }
    
    public function onReverse(ReverseEvent $event): void 
    {
        if($this->security->isGranted("ROLE_USER")){
            $event->getOriginalData()->setUser($this->security->getUser());
        }
        //dd($event);
        $event->getOriginalData()->setAuthor($event->getData()->getAuthor());
        $event->getOriginalData()->setContent($event->getData()->getContent());
    }
}
