<?php

namespace MVC\Modules;

class Service{

  protected $s = array();

  /**
   * Set an object or closure
   *
   * @param string $key name
   * @param mixed $callable closure or object instance
   */
  function __set($key, $callable){
    // Like normal PHP, property/method names should be case-insensitive.
    $key = strtolower($key);

    // Simple object storage?
    if( ! $callable instanceof \Closure){
      $this->s[$key] = $callable;
      return;
    }

    // Create singleton wrapper function tied to this service object
    $this->s[$key] = function ($c, array $arg) use ($callable){
      static $object;
      if (is_null($object)){
        array_unshift($arg, $c);
        $object = call_user_func_array($callable, $arg);
      }
      return $object;
    };
  }


  /**
   * Fetch an object or closure
   *
   * @param string $key name
   * @return mixed
   */
  function __get($key){
    return $this->s[$key];
  }

  /**
   * Check that the given key name exists
   *
   * @param string $key name
   * @return mixed
   */
  function __isset($key){
    return isset($this->s[$key]);
  }


  /**
   * Call the given closure singleton function
   *
   * @param string $key name
   * @param array $arg for closure
   * @return mixed
   */
  function __call($key, $arg){
    return $this->s[$key]($this, $arg);
  }

}
?>