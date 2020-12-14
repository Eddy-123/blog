<?php

namespace App\Handler;

use Symfony\Component\HttpFoundation\Request;
use App\Handler\HandlerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

abstract class AbstractHandler implements HandlerInterface{

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    
    /**
     * @var FormInterface
     */
    private $form;


    abstract protected function getFormType(): string;
    
    abstract protected function process($data): void;
    
    /**
     * @required
     */
    public function setFormFactory(FormFactoryInterface $formFactory): void {
        $this->formFactory = $formFactory;
    }
    
    public function handle(Request $request, $data, array $options = []): bool {
        $this->form = $this->formFactory->create($this->getFormType(), $data)->handleRequest($request);
        
        if($this->form->isSubmitted() && $this->form->isValid()){
            $this->process($data);
            
            return true;
        }
        
        return false;
    }
    
    public function createView(): FormView {
        return $this->form->createView();;
    }
}
