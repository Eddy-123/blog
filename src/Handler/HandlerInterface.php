<?php

namespace App\Handler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormView;

interface HandlerInterface {
    
    /**
     * @param mixed $data
     */
    public function handle(Request $request, object $originalData, array $options = []): bool;
    
    public function createView(): FormView;
}
