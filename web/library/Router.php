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
        if (!is_null($defaultValue)) $default = '?';
        $route = str_replace('{'.$param.'}','?(?P<'.$param.'>'.$restriction.')'.$default, $route);
      }
    }
    return $route;
  }

  public function generate($routeName, $params = array(), $absolute = false){
    if (array_key_exists($routeName, $this->routes)){
      $route = $this->routes[$routeName];
      $url = $route['pattern'];

      foreach ($params as $key => $value) {
        $url = str_replace('{'.$key.'}',$value, $url);
      }

      $defaults = @$route['defaults'];
      if (is_array($defaults)){
        foreach ($defaults as $key => $value){
          if (!array_key_exists($key, $params)){
            $url = str_replace('{'.$key.'}',$value, $url);
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


  /**
   * Parse the given URL path and return the correct controller and parameters.
   *
   * @param string $path segment of URL
   * @param array $routes to test against
   * @return array
   */
  public function route($path){
    if (substr($path, 0, strlen($this->ignore)) == $this->ignore) {
      $path = substr($path, strlen($this->ignore));
    } 

    if($path === ''){
      return array(array(), '', $this->routes['index']);
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
        return array($matches, $route, $controller);
      }
    }

    return array(array(), $path, $this->routes['404']);
  }
}
