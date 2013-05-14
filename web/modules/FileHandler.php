<?php

namespace MVC\Modules;

class FileHandler{

  public function __construct(){}

  public function exists($fileName) {
    if($fileName != '') {
      if(file_exists($fileName)) {
        return true;
      }else {
        return false;
      }
    }else {
      return false;
    }
  }

}
?>