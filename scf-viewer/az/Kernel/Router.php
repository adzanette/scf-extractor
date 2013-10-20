<?php

namespace AZ\Framework\Kernel;

class Router{

  private $routes;
  private $ignore;
  private $domain;

  public function __construct(array $routes, $ignore = '', $domain){
    $this->routes = $routes;
    $this->ignore = $ignore;
    $this->domain = $domain;
  }

  public function replaceNamedGroups($route, $restrictions, $defaults){
    if (preg_match_all("/{\w+}/i", $route, $matches) > 0){
      $params = $matches[0];
      foreach ($params as $param) {
        $param = str_replace(array('{','}'), "", $param);
        $restriction = @$restrictions[$param];
        $defaultValue = @$defaults[$param];
        if (!$restriction) $restriction = '[^/]+';
        !is_null($defaultValue) ? $default = '?' : $default = ''; 
        $route = str_replace('{'.$param.'}',$default.'(?P<'.$param.'>'.$restriction.')'.$default, $route);
      }
    }
    return $route;
  }

  public function generate($routeName, $params = array(), $absolute = false, $withDefaults = false){
    if (array_key_exists($routeName, $this->routes)){
      $route = $this->routes[$routeName];
      $url = $route['pattern'];

      foreach ($params as $key => $value) {
        $url = str_replace('{'.$key.'}',$value, $url);
      }

      if ($withDefaults){
        $defaults = @$route['defaults'];
        if (is_array($defaults)){
          foreach ($defaults as $key => $value){
            if (!array_key_exists($key, $params)){
              $url = str_replace('{'.$key.'}',$value, $url);
            }
          }
        }
      }
      
      $url = $this->ignore.$url;
      if ($absolute){
        $url = $this->domain.$url;
      }else if ($url[0] != '/'){
        $url = '/'.$url;
      }
      
      return $url;
    }else{
      return null;
    }   
  }

  public function getRoute($route){
    return array_key_exists($route, $this->routes) ? $this->routes[$route] : null;
  }


  public function route($path){
    
    // accepting repeated slashes
    $path = preg_replace("/\/+/", "/", $path);

    // removing trailer slash
    $path = trim($path, '/');

    // ignoring some initial path
    if (substr($path, 0, strlen($this->ignore)) == $this->ignore) {
      if (strlen($path) === strlen($this->ignore)){
        $path = '';
      }else{
        $path = substr($path, strlen($this->ignore));
      }
    } 

    // if blank is index
    if($path === ''){
      return array('index', array());
    }

    foreach($this->routes as $route => $controller){
      // skipping index
      if($route == 'index') continue;

      // generating regex from route pattern and defaults
      $defaults = @$controller['defaults'];
      $restrictions = @$controller['restrictions'];
      $pattern = $this->replaceNamedGroups($controller['pattern'], $restrictions, $defaults);
      $regex = str_replace('/','\/',$pattern);
      
      // matching pattern and path
      if (preg_match("/^$regex\/?$/i", $path, $matches) === 1) {
        // if match, get path parameters
        $complete = array_shift($matches);
        foreach ($matches as $key => $value){
          if (is_numeric($key)){
            unset($matches[$key]);
          }
        }

        // getting defaults, if not matched
        if (is_array($defaults)){
          foreach ($defaults as $key => $value){
            if (!array_key_exists($key, $matches)){
              $matches[$key] = $value;
            }
          }
        }

        return array($route, $matches);
      }
    }

    // if doesnt found route, goes to 404
    return array('404', array());
  }
}
