<?php

namespace MVC\Library;

class Router{

  private $routes;
  private $ignore;

  public function __construct(array $routes, $ignore = ''){
    $this->routes = $routes;
    $this->ignore = $ignore;
  }

  public function replaceNamedGroups($route, $restrictions){
    if (preg_match_all("/{\w+}/i", $route, $matches) > 0){
      $params = $matches[0];
      foreach ($params as $param) {
        $restriction = @$restrictions[$param];
        if (!$restriction) $restriction = '[a-zA-Z0-9_-]+';
        $param = str_replace(array('{','}'), "", $param);
        $route = str_replace('{'.$param.'}','(?P<'.$param.'>'.$restriction.')', $route);
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
    $path = trim($path, '/');

    if($path === ''){
      return array(array(), '', $this->routes['']);
    }

    foreach($this->routes as $route => $controller){
      if(!$controller['pattern']) continue; // Skip homepage route

      $pattern = $this->replaceNamedGroups($controller['pattern'], @$controller['restrictions']);
      $regex = str_replace('/','\/',$pattern);
      if (preg_match("/$regex/i", $path, $matches) === 1) {
        $complete = array_shift($matches);

        foreach ($matches as $key => $value){
          if (is_numeric($key)){
            unset($matches[$key]);
          }
        }

        return array($matches, $route, $controller);
      }

    }

    // Controller not found
    return array(array($path), $path, $this->routes['404']);
  }
}
