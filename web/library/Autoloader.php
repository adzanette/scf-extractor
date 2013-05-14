<?php

namespace MVC\Library;

class Autoloader{
  
  public function __construct($directories){
    foreach ($directories as $directory){
      self::scanDirectories($directory);
    }
  }

  public static function scanDirectories($rootDir){
    $invisibleFileNames = array(".", "..", ".htaccess", ".htpasswd");
    $dirContent = scandir($rootDir);
    foreach($dirContent as $key => $content){
      $path = $rootDir.'/'.$content;
      if(!in_array($content, $invisibleFileNames)){
        if(is_dir($path) && is_readable($path)){
          self::scanDirectories($path);
        }else if (is_file($path)){
          $fileParts = pathinfo($content);
          if ($fileParts['extension'] === 'php'){
            require_once $path;
          }
        }
      }
    }
    return true;    
  }
}