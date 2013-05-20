<?php

namespace AZ\Framework\Kernel;

class ParameterBag implements \IteratorAggregate, \Countable{
    
  protected $parameters;

  public function __construct(array $parameters = array()){
    $this->parameters = $parameters;
  }

  public function all(){
    return $this->parameters;
  }

  public function keys(){
    return array_keys($this->parameters);
  }

  public function replace(array $parameters = array()){
    $this->parameters = $parameters;
  }

  public function add(array $parameters = array()){
    $this->parameters = array_replace_recursive($this->parameters, $parameters);
  }
  
  public function get($key, $default = null){
    if (strpos($key, '/') === false){
      return array_key_exists($key, $this->parameters) ? $this->parameters[$key] : $default;
    }else{
      $keys = explode('/', $key);
      $value = $this->parameters;
      foreach ($keys as $key){
        if(array_key_exists($key, $value)){
          $value = $value[$key];
        }else{
          $value = $default;
          break;
        }
      }
      return $value;
    }
  }

  public function setRecursive($keys, $value){
    $key = array_shift($keys);
    if (count($keys) > 0){
      return array($key => $this->setRecursive($keys, $value));
    }else{
      return array($key => $value);
    }
  }

  public function set($key, $value){
    if (strpos($key, '/') === false){
      $this->parameters[$key] = $value;
    }else{
      $keys = explode('/', $key);
      $replace = $this->setRecursive($keys, $value);
      $this->add($replace);
    }
  }

  public function has($key){
    if (strpos($key, '/') === false){
      return array_key_exists($key, $this->parameters);
    }else{
      $keys = explode('/', $key);
      $value = $this->parameters;
      foreach ($keys as $key){
        if(array_key_exists($key, $value)){
          $value = $value[$key];
        }else{
          return false;
        }
      }
      return true;
    }
  }

  private function removeElement(&$params, $keys) { 
    $key = array_shift($keys);
    if (count($keys) > 0) {
      $value = &$params[$key]; 
      $this->removeElement($value, $keys); 
    } else {
      unset($params[$key]);
    } 
  }

  public function remove($key){
    if (strpos($key, '/') === false){
      unset($this->parameters[$key]);
    }else{
      $keys = explode('/', $key);
      $this->removeElement($this->parameters, $keys);
    }
  }

  public function getAlpha($key, $default = ''){
    return preg_replace('/[^[:alpha:]]/', '', $this->get($key, $default));
  }

  public function getAlnum($key, $default = ''){
    return preg_replace('/[^[:alnum:]]/', '', $this->get($key, $default));
  }

  public function getDigits($key, $default = ''){
    return str_replace(array('-', '+'), '', $this->filter($key, $default, FILTER_SANITIZE_NUMBER_INT));
  }

  public function getInt($key, $default = 0){
      return (int) $this->get($key, $default);
  }
  
  public function getDate($key, \DateTime $default = null){
    if (null === $value = $this->get($key)) {
      return $default;
    }

    if (false === $date = \DateTime::createFromFormat(DATE_RFC2822, $value)) {
      throw new \RuntimeException(sprintf('The %s HTTP header is not parseable (%s).', $key, $value));
    }

    return $date;
  }

  public function filter($key, $default = null, $filter=FILTER_DEFAULT, $options=array()){
    $value = $this->get($key, $default);

    if (!is_array($options) && $options) {
      $options = array('flags' => $options);
    }

    if (is_array($value) && !isset($options['flags'])) {
      $options['flags'] = FILTER_REQUIRE_ARRAY;
    }

    return filter_var($value, $filter, $options);
  }

  public function getIterator(){
      return new \ArrayIterator($this->parameters);
  }

  public function count(){
      return count($this->parameters);
  }
}
