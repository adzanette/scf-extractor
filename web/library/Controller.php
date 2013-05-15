<?php
namespace MVC\Library;

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
  public function initialize($params) {}


  public function render($view, $params){
    $template = $this->context->template;
    extract((array) $params);
    ob_start();
    require __DIR__.'/../resources/view/'.$view;
    $page = ob_get_clean();
    
    $template->setContent($page);
    $html = $template->getPage();

    $response = new Response($html);
    return $response;
  }

  //implement redirect
  //implement foward
  //implement get and has for retrieve context

}

// End