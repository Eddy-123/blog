<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Event\ReverseEvent;
use App\Event\TransferEvent;
use Symfony\Component\Security\Core\Security;
use App\Entity\Comment;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class RegistrationSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;
    
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder) {
        $this->userPasswordEncoder = $userPasswordEncoder;
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
        return;
    }
    
    public function onReverse(ReverseEvent $event): void 
    {        
        if(!$event->getOriginalData() instanceof User){
            return;
        }
        
        $event->getOriginalData()->setPseudo($event->getData()->getPseudo());
        $event->getOriginalData()->setEmail($event->getData()->getEmail());
        $event->getOriginalData()->setPassword(
        $this->userPasswordEncoder->encodePassword(
                $event->getOriginalData(), 
                $event->getData()->getPassword())
        );
    }
}
