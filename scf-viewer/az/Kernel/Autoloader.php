<?php
namespace AZ\Framework\Kernel;

class Autoloader{
  private $namespaces = array();
  private $fallback;
  private $separator = DIRECTORY_SEPARATOR;

  public function registerNamespaceFallback($dir) {
    $this->fallback = $dir;
  }

  public function registerNamespaces(array $namespaces) {
    foreach ($namespaces as $namespace => $locations) {
      $this->namespaces[$namespace] = (array) $locations;
    }
  }

  public function register(){
    spl_autoload_register(array($this, 'loadClass'), true);
  }

  public function loadClass($class){
    if ($file = $this->findFile($class)) {
      require $file;
      return true;
    }
  }

  public function findFile($class){
    if ($class[0] == '\\'){
      $class = substr($class, 1);
    }

    if (($pos = strrpos($class, '\\')) !== false){
      $namespace = substr($class, 0, $pos);
      foreach ($this->namespaces as $ns => $dirs){
        if (strpos($namespace, $ns) !== 0) {
          continue;
        }
        
        $className = substr($class, strlen($ns));
        $normalizedClass = str_replace('\\', $this->separator, $className).'.php';

        foreach ($dirs as $dir){
          $file = $dir.$this->separator.$normalizedClass;
          if (is_file($file)){
            return $file;
          }
        }
      }

      if (!is_null($this->fallback)){
        $file = $this->fallback.$this->separator.str_replace('\\', $this->separator, $class).'.php';
        if (is_file($file)){
          return $file;
        }
      }
    }
  }
}
