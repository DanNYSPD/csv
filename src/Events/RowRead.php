<?php
namespace Xarenisfot\Csv\Events;

use League\Event\Event;
use League\Event\EventInterface;


class RowRead //extends Event{
  //  implements EventInterface
  extends Event
    {
    public function __construct()
    {
        $this->name = self::NAME;
    }
    public const NAME = 'row.read';
    
    public function getName(){
        return self::NAME;
    }
    public $index;
    /**
     * this can be a array or an object if a mapper was used
     *
     * @var mixed
     */
    public $data;
}
