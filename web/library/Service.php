<?php

namespace MVC\Library;

class Service{

  protected $services = array();

  public function __construct($services){
    foreach($services as $key => $value){
      $this->$key = $value;
      $this->services[$key] = $value;  
    } 
  }

  public function set($serviceName, $service){
    $this->$serviceName = $service;
    $this->services[$serviceName] = $service;
  }

  function __get($key){
    return $this->s[$key];
  }

}
?>