<?php
namespace Xarenisfot\Csv;

use Exception;
use Xarenisfot\Csv\Events\RowRead;
use Symfony\Component\EventDispatcher\EventDispatcher;
/**
 * @author Daniel Hernandez Fco <daniel.hernandez.job@gmail.com>
 * 
 * 
 */
class CsvReader {
    
    
    public $columnsNumber=-1;
    public $offset=-1;
    public $delimiter=',';
    public $limit=-1;
    /**
     * Undocumented variable
     *
     * @var EventDispatcher
     */
    protected $dispatcher;

    public function __construct(){
        $this->dispatcher = new EventDispatcher();
    }
    public function addListener($callable){
        $this->dispatcher->addListener('row.read',$callable);
    }
    public  function readCSVReturn(string $csvFilePath,callable $rowReaderObject=null)
    {
        $arrObjects=[];
        $row = 1;
        if (($handle = fopen($csvFilePath, "r")) !== false) {
            $previous=null;
            while (($data = fgetcsv($handle, 1000, $this->delimiter)) !== false) {
                $num = count($data);
                if(!empty($previous)){//if there is previous data is supposed that current data is part of it
                    if(count($data)===$this->columnsNumber){
                        throw new Exception("There is a previous incomplete data and the current row is complete, an incomplete row was expected to be prepended to its previous data");
                    }
                    $data=\array_merge($previous,$data);
                    $previous=null;
                    if(count($data)!==$this->columnsNumber){
                        throw new Exception("An error ocurred while trying to resolved bad formatted csv file at row $row, columns number expected is {$this->columnsNumber},");
                    }
                }

              if(($row<=$this->offset)){
                $row++;
                continue;
              }
              if($num!==$this->columnsNumber){
                
                //if there is not enought columns jump to the next and try to resolve it!
                $previous=$data;
                continue;
              }
              $rowRead= new RowRead();
              $rowRead->index=$row;

              if (is_callable($rowReaderObject)) {
                  $object=$rowReaderObject($data, $num, $row);
                  if (null==$object) {
                      throw new Exception("Callable must return a value");
                  }
                  $arrObjects[]=  $object;
                  
                  $rowRead->data=$object;
              }else{
                  $arrObjects[]=  $data;
                  $rowRead->data=$data;
                  
              }
              $this->dispatcher->dispatch($rowRead,RowRead::NAME);
              $row++;
            }
            fclose($handle);
        }else{
            throw new Exception("The file couldn't be read");
        }
        return $arrObjects;
    }
}