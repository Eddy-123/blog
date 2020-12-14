<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;


class ReverseEvent extends Event
{
    const NAME = "app.event.reverse";
    
    /**
     * @var object
     */
    private $data;
    
    /**
     * @var object
     */
    private $originalData;
    
    public function __construct(object $data, object $originalData) {
        $this->data = $data;
        $this->originalData = $originalData;
    }

    public function getData(): object {
        return $this->data;
    }

    public function getOriginalData(): object {
        return $this->originalData;
    }

    public function setData(object $data): void {
        $this->data = $data;
    }

    public function setOriginalData(object $originalData): void {
        $this->originalData = $originalData;
    }


}
