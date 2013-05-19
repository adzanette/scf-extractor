<?php
namespace AZ\Framework;

class File extends \DirectoryIterator{
  
  private $path;

  public function __construct($path){
    $this->path = $path;
    parent::__construct($path);
  }

  public function get(){
    return file_get_contents($path);
  }

  public function exists(){
    return file_exists($this->path);
  }

  public function getExtension(){
    return pathinfo($this->path, PATHINFO_EXTENSION); 
  }


}

?>