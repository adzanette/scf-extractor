<?php

namespace MVC\Library;

class Application{
  
  private $router;
  private $routes;
  private $conf;
  private $context;

  public function __construct($conf, $routes){
    $this->conf = $conf;
    $this->routes = $routes;
    $this->loadServices();
  }

  private function loadServices(){
    
    $this->router = new Router($this->routes, $this->conf->get('router/ignore'), $this->conf->get('router/domain'));
    
    $translator = new Translator($this->conf->get('translator/folder'), $this->conf->get('translator/domain'), $this->conf->get('default-locale'));
    
    $templateConfig = $this->conf->get('template');
    $template = new Template($templateConfig);
    $template->setTranslator($translator);
    $template->setRouter($this->router);

    $database = new Database($this->conf->get('database'));
    if(empty(ORM::$database)){
      ORM::$db = $database;
    }

    $services = array(
       'settings' => $this->conf
      ,'request' => Request::createFromGlobals()
      ,'session' => new Session()
      ,'router' => $this->router
      ,'database' => $database
      ,'template' => $template
    );

    $this->context = new Service($services);
  }

  public function handleRequest(){  
    $path = $this->context->request->server->get('request-uri');
   
    list($params, $route, $execute) = $this->router->route($path);
    extract($execute);

    $controller = '\\MVC\\Controller\\'.$controller;
    $control = new $controller($this->context);

    if(!method_exists($control, $method)) 
      throw new \Exception('Invalid Request Method.');

    $control->initialize($params);
    if($params){
      $response = call_user_func_array(array($control, $method), $params);
    }else{
      $response = $control->$method();
    }

    if (!$response instanceof Response) 
      throw new \Exception('Controller must return a Response Object');

    return $response->send();
  }

  //implement handle

}

?>