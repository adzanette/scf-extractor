<?php

namespace AZ\Framework;

class Application{
  
  private $router;
  private $settings;
  private $context;

  public function __construct($directory, $conf, $routes){
    $this->settings = new ParameterBag($conf);
    $this->settings->set('root-directory', $directory);
    $this->router = new Router($routes, $this->settings->get('router/ignore'), $this->settings->get('router/domain'));
    $this->request = Request::createFromGlobals();
    
    $this->loadServices();
  }

  private function loadServices(){
    $database = new Database($this->settings->get('database'));
    $translator = new Translator($this->settings->get('translator/folder'), $this->settings->get('translator/domain'), $this->settings->get('default-locale'));
    
    $templateConfig = $this->settings->get('template');
    $template = new Template($templateConfig);
    $template->setTranslator($translator);
    $template->setRouter($this->router);

    $services = array(
       'settings' => $this->settings
      ,'request' => $this->request
      ,'session' => new Session()
      ,'router' => $this->router
      ,'database' => $database
      ,'template' => $template
    );

    $this->context = new Service($services);
  }

  public function handleRequest(){  
    $path = $this->request->server->get('PHP_SELF');

    list($route, $params) = $this->router->route($path);
    
    $response = $this->handle($route, $params); 

    if (!$response instanceof Response) 
      throw new \Exception('Controller must return a Response Object');

    return $response->send();
  }

  public function handle($route, $params){
    $routeParams = $this->router->getRoute($route);
    extract($routeParams);

    $control = new $controller($this->context, $this);

    if(!method_exists($control, $method)) 
      throw new \Exception('Invalid Request Method.');

    $control->initialize($params);
    if($params){
      $response = call_user_func_array(array($control, $method), $params);
    }else{
      $response = $control->$method();
    }

    return $response;
  }
}

?>