<?php

namespace MVC\Modules;

class Session{
  private $name='Default';
 
  public function __constructor($name){
    session_start();
    $this->name = $name;
  }

  public function getName(){
    return $this->name;
  }

  public function set($key, $value){
    $_SESSION[$this->name][$key] = $value;
  }

  public function get($key, $default=null){
    if(isset($_SESSION[$this->name][$key]) && !empty($_SESSION[$this->name][$key])){
        return $_SESSION[$this->name][$key];
    }else{
      return $default;
    }
  }
}
?>