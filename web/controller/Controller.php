<?php
namespace MVC\Controller;

abstract class Controller{
  
  // URL path segment matched to route here
  public $context;


  /**
   * Set error handling and start session
   */
  public function __construct($context){
    $this->context = $context;
  }


  /**
   * Called before the controller method is run
   *
   * @param string $method name that will be run
   */
  public function initialize($method, $params) {}

  /**
   * Called after the controller method is run to send the response
   */
  public function send() {}

}

// End