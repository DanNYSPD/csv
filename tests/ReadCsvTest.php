<?php 

use Xarenisfot\Csv\CsvReader;
use PHPUnit\Framework\TestCase;
use Xarenisfot\Csv\Events\RowRead;
class Person{
    public $name;
    public $lastName;
    public $country;
    public $age;
}
final class ReadCsvTest extends TestCase {

    public function testReadSimpleCsvNotCallable(){
        $reader= new CsvReader();
        $reader->columnsNumber=4;
        $data= $reader->readCSVReturn(__DIR__."/assets/sample.csv");
        $this->assertEquals(8,count($data));
        //print_r($data);
    }
    public function testReadSimpleCsvCallable(){
        $reader= new CsvReader();
        $reader->columnsNumber=4;
        $data= $reader->readCSVReturn(__DIR__."/assets/sample.csv",function($data,$columnsNumber,$rowNumber){
            $p=new Person();
            $p->name=$data[0];
            $p->lastName=$data[1];
            $p->country=$data[2];
            $p->age=$data[3];
            return $p;
        });
        $this->assertEquals(8,count($data));
       // print_r($data);
    }
    public function testReadSimpleCsvCallableWithOffset(){
        $reader= new CsvReader();
        $reader->columnsNumber=4;
        $reader->offset=1;
        $data= $reader->readCSVReturn(__DIR__."/assets/sample.csv",function($data,$columnsNumber,$rowNumber){
            $p=new Person();
            $p->name=$data[0];
            $p->lastName=$data[1];
            $p->country=$data[2];
            $p->age=$data[3];
            return $p;
        });
        //as we ignore the first element just seven items must be returned
        $this->assertEquals(7,count($data));        
    }
    public function testReadSimpleCsvListener(){
        $reader= new CsvReader();
        $reader->columnsNumber=4;
        $reader->offset=1;
        $reader->addListener(function(RowRead $evt){
            echo "$evt->index";
            echo "\n";
            print_r ($evt->data);
        });
        $data= $reader->readCSVReturn(__DIR__."/assets/sample.csv",function($data,$columnsNumber,$rowNumber){
            $p=new Person();
            $p->name=$data[0];
            $p->lastName=$data[1];
            $p->country=$data[2];
            $p->age=$data[3];
            return $p;
        });
        //as we ignore the first element just seven items must be returned
        $this->assertEquals(7,count($data));        
    }

    
}