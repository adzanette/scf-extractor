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
    
    $this->router = new Router($this->routes, $this->conf->get('router/ignore'));
    
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
      ,'router' => $this->router
      ,'session' => new Session()
      ,'template' => $template
      ,'request' => Request::createFromGlobals()
      ,'database' => $database
    );

    $this->context = new Service($services);
  }

  public function handleRequest(){  
    $server = $this->context->request->server;
    $path = $server->get('REQUEST_URI');
   
    list($params, $route, $execute) = $this->router->route($path);

    $controllerName = '\\MVC\\Controller\\'.$execute['controller'];
    $methodName = $execute['method'];

    $controller = new $controllerName($this->context);

    if(!method_exists($controller, $methodName)){
      throw new \Exception('Invalid Request Method.');
    }

    $controller->initialize($params);

    if($params){
      list($view, $viewParams) = call_user_func_array(array($controller, $methodName), $params);
    }else{
      list($view, $viewParams) = $controller->$methodName();
    }

    $template = $this->context->template;
    extract((array) $viewParams);
    ob_start();
    require __DIR__.'/../resources/view/'.$view;
    $page = ob_get_clean();
    
    $template->setContent($page);
    $html = $template->getPage();

    $response = new Response($html);
    $response->send();
    
    return true;
  }

}

?>