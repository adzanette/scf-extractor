<?php
namespace MVC\Controller;

class SiteController extends \MVC\Library\Controller{
  
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
  public function initialize($method, $params) {

  }

  public function test(){
    return array('index.html.php', array('teste' => 'blah'));
  }

  /**
   * Called after the controller method is run to send the response
   */
  public function send() {}

}

// End