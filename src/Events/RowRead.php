<?php
namespace Xarenisfot\Csv\Events;

use Symfony\Contracts\EventDispatcher\Event;

class RowRead extends Event{
    
    public const NAME = 'row.read';

    public $index;
    /**
     * this can be a array or an object if a mapper was used
     *
     * @var mixed
     */
    public $data;
}
