<?php
namespace MVC\Modules;

final class Settings{

  private $json;
  private $config;
  
  public function __construct($file){
    $this->json = json_decode(file_get_contents($file), true);
    $this->proccessJson();
  }

  private function proccessJson(){
    foreach ($this->json as $key => $value){
      $key = strtolower($key);
      if (is_string($value)){
        $this->config[$key] = $value; 
      }else{
        $this->config[$key] = $this->proccessComplexValue($value);
      }
    }
  }

  private function proccessComplexValue($complexValue){
    $value = @$complexValue['value'];

    foreach ($complexValue as $operator => $reference){
      switch (strtolower($operator)){
        case 'mergebefore':
          $value = $this->config[$reference].$value;
          break;
        case 'mergeafter':
          $value = $value.$this->config[$reference];
          break;
        case 'equalto':
          $value = $this->config[$reference];
          break;
        default:
          break;
      }
    }

    return $value;
  }

  public function get($key){
    $key = strtolower($key);
    if(array_key_exists($key, $this->config)){
      return $this->config[$key];
    }else{
      return null;
    }
  }
}
?>