<?php

namespace App\Handler;

use Symfony\Component\HttpFoundation\Request;
use App\Handler\HandlerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use App\Event\TransferEvent;
use App\Event\ReverseEvent;

abstract class AbstractHandler implements HandlerInterface
{
    
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    
    /**
     * @var FormInterface
     */
    protected $form;

    abstract protected function getDataTransferObject(): object;

    abstract protected function getFormType(): string;
    
    abstract protected function process($data): void;

    /**
     * @required
     * @param EventDispatcherInterface $eventDispatcher
     * @return void
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): void {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @required
     */
    public function setFormFactory(FormFactoryInterface $formFactory): void {
        $this->formFactory = $formFactory;
    }
    
    public function handle(Request $request, object $originalData, array $options = []): bool {
        $data = $this->getDataTransferObject();
        
        $this->eventDispatcher->dispatch(new TransferEvent($originalData, $data), TransferEvent::NAME);
        
        $this->form = $this->formFactory->create($this->getFormType(), $data, $options)->handleRequest($request);
        
        if($this->form->isSubmitted() && $this->form->isValid()){
            $this->eventDispatcher->dispatch(new ReverseEvent($data, $originalData), ReverseEvent::NAME);

            $this->process($originalData);
            
            return true;
        }
        
        return false;
    }
    
    public function createView(): FormView {
        return $this->form->createView();;
    }
}
