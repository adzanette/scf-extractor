<?php

namespace MVC\Library;

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
        if (!$restriction) $restriction = '[a-zA-Z0-9_-]+';
        $default = '';
        if (!is_null($defaultValue)) $default = '?';
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
      if ($absolute) $url = $this->domain.$url;

      return $url;
    }else{
      return null;
    }   
  }

  public function getRoute($route){
    return array_key_exists($route, $this->routes) ? $this->routes[$route] : null;
  }


  public function route($path){
    if (substr($path, 0, strlen($this->ignore)) == $this->ignore) {
      if (strlen($path) === strlen($this->ignore)){
        $path = '';
      }else{
        $path = substr($path, strlen($this->ignore));
      }
    } 

    if($path === ''){
      return array(array(), 'index');
    }

    foreach($this->routes as $route => $controller){
      if($route == 'index') continue;

      $defaults = @$controller['defaults'];
      $restrictions = @$controller['restrictions'];
      $pattern = $this->replaceNamedGroups($controller['pattern'], $restrictions, $defaults);
      $regex = str_replace('/','\/',$pattern);
      
      if (preg_match("/^$regex\/?$/i", $path, $matches) === 1) {
        $complete = array_shift($matches);
        foreach ($matches as $key => $value){
          if (is_numeric($key)){
            unset($matches[$key]);
          }
        }

        if (is_array($defaults)){
          foreach ($defaults as $key => $value){
            if (!array_key_exists($key, $matches)){
              $matches[$key] = $value;
            }
          }
        }
        return array($matches, $route);
      }
    }

    return array(array(), '404');
  }
}
