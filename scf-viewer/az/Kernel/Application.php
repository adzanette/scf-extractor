<?php
namespace AZ\Framework\Kernel;

use AZ\Framework\HTTP\Request;
use AZ\Framework\HTTP\Response;
use AZ\Framework\Kernel\ParameterBag;
use AZ\Framework\Cache\CacheHandler;
use AZ\Framework\Database;
use AZ\Framework\Template\Template;
use AZ\Framework\Template\Translator;
use AZ\Framework\HTTP\Session;

class Application{
  
  private $router;
  private $settings;
  private $context;

  public function __construct($directory, $conf, $routes){
    $this->settings = new ParameterBag($conf);
    $this->settings->set('root-directory', $directory.DIRECTORY_SEPARATOR);
    if ($this->settings->has('view-directory')){
      $viewDirectory = $this->settings->get('root-directory').$this->settings->get('root-directory');
    }else{
      $viewDirectory = $this->settings->get('root-directory').'resources'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR;
    }
    $this->settings->set('view-directory', $viewDirectory);
    
    $this->router = new Router($routes, $this->settings->get('router/ignore'), $this->settings->get('router/domain'));
    $this->request = Request::createFromGlobals();
    
    $this->loadServices();
  }

  private function loadServices(){
    $cache = new CacheHandler($this->settings->get('cache'));
    $database = new Database($this->settings->get('database'));
    $translator = new Translator($this->settings->get('translator/folder'), $this->settings->get('translator/domain'), $this->settings->get('template/locale'), $cache);
    
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
      ,'cache' => $cache
    );

    $this->context = new Service($services);
  }

  public function handleRequest(){  
    $path = $this->request->server->get('REQUEST_URI');

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