<?php
namespace AZ\Framework;

final class Settings{

  private $config;
  
  public function __construct($conf){
    $this->config = $conf;
  }
 
  private function getComplexValue($keys, $config){
    $key = array_shift($keys);
    if(array_key_exists($key, $config)){
      if (count($keys) == 0){
        return $config[$key];
      }else{
        return $this->getComplexValue($keys, $config[$key]);
      }
    }else{
      return null;
    }
  }

  public function get($key){
    if (strpos($key, '/') === false){
      $key = strtolower($key);
      if(array_key_exists($key, $this->config)){
        return $this->config[$key];
      }else{
        return null;
      }
    }else{
      return $this->getComplexValue(explode('/', $key), $this->config);
    }
  }
}
?>