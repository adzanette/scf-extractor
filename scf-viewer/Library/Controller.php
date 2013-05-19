<?php
namespace MVC\Library;

abstract class Controller{
  
  public $context;
  public $app;

  public function __construct($context, $app){
    $this->context = $context;
    $this->app = $app;
  }

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

  public function forward($route, $params){
    return $this->app->handle($route, $params);
  }

  public function get($serviceName){
    return $this->context->get($serviceName);
  }

  public function set($serviceName, $service){
    return $this->context->set($serviceName, $service);
  }

  public function redirect($url, $status = 302, $headers = array()){
    return new RedirectResponse($url, $status, $headers);
  }

}
