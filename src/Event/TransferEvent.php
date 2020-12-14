<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class TransferEvent extends Event
{
    const NAME = "app.transfer.reverse";
    /**
     * @var object
     */
    private $originalData;
    
    /**
     * @var object
     */
    private $data;

    public function __construct(object $originalData, object $data) {
        $this->originalData = $originalData;
        $this->data = $data;
    }

    public function getOriginalData(): object {
        return $this->originalData;
    }

    public function getData(): object {
        return $this->data;
    }
}
