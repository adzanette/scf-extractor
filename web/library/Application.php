<?php

namespace MVC\Library;

class Application{
  
  private $router;
  private $conf;
  private $context;

  public function __construct($conf){
    $this->conf = $conf;
    $this->loadServices();
  }

  private function loadServices(){
    
    include __DIR__.'/conf/routes.php';
    $this->router = new Router($routes, $this->conf->get('router-ignore'));
    
    $template = new Template($this->conf->get('url-media'), $this->conf->get('url-media-js'), $this->conf->get('url-media-css'), $this->conf->get('url-media-img'), $this->conf->get('version'), $this->conf->get('title'), $this->conf->get('locale'));

    $this->context = new Service();
    $this->context->configuration = function(){
      return $this->configuration;
    };

    $this->context->router = function(){
      return $this->router;
    };

    $this->context->session = function(){
      return new Session($this->conf->get('session-name'));
    };

    $this->context->template = function(){
      return $template;
    };

    $db = new Database($this->conf->get('database'));
    if(empty(ORM::$db)){
      ORM::$db = $db;
    }

    $this->context->database = function(){
      return $db;  
    }

  }

  public function handleRequest(){
    $path = $_SERVER['REQUEST_URI'];

    list($params, $route, $execute) = $this->router->route($path);

    $controllerName = '\\MVC\\Controller\\'.$execute['controller'];
    $methodName = $execute['method'];

    $controller = new $controllerName($this->context);

    if(!method_exists($controller, $methodName)){
      if(!method_exists($controller, 'run')){
        throw new \Exception('Invalid Request Method.');
      }

      $method = 'run';
    }

    $controller->initialize($methodName, $execute);

    if($params){
      call_user_func_array(array($controller, $methodName), $params);
    }else{
      $controller->$methodName();
    }

    return $controller;
  }

}

?>