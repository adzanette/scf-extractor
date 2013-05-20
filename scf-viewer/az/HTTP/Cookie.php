<?php

namespace AZ\Framework\HTTP;

class Cookie{
  protected $name;
  protected $value;
  protected $domain;
  protected $expire;
  protected $path;
  protected $secure;
  protected $httpOnly;

  public function __construct($name, $value = null, $expire = 0, $path = '/', $domain = null, $secure = false, $httpOnly = true){
    if (preg_match("/[=,; \t\r\n\013\014]/", $name)){
      throw new \Exception(sprintf('The cookie name "%s" contains invalid characters.', $name));
    }

    if (empty($name)){
      throw new \Exception('The cookie name cannot be empty.');
    }

    if ($expire instanceof \DateTime){
      $expire = $expire->format('U');
    }elseif (!is_numeric($expire)){
      $expire = strtotime($expire);

      if (false === $expire || -1 === $expire){
        throw new \Exception('The cookie expiration time is not valid.');
      }
    }

    $this->name = $name;
    $this->value = $value;
    $this->domain = $domain;
    $this->expire = $expire;
    $this->path = empty($path) ? '/' : $path;
    $this->secure = (Boolean) $secure;
    $this->httpOnly = (Boolean) $httpOnly;
  }

  public function __toString(){
    $str = urlencode($this->getName()).'=';

    if (((string) $this->getValue()) === ''){
      $str .= 'deleted; expires='.gmdate("D, d-M-Y H:i:s T", time() - 31536001);
    }else{
      $str .= urlencode($this->getValue());

      if ($this->getExpiresTime() !== 0){
        $str .= '; expires='.gmdate("D, d-M-Y H:i:s T", $this->getExpiresTime());
      }
    }

    if ($this->path !== '/'){
      $str .= '; path='.$this->path;
    }

    if ($this->getDomain() !== null){
      $str .= '; domain='.$this->getDomain();
    }

    if ($this->isSecure() === true){
      $str .= '; secure';
    }

    if ($this->isHttpOnly() === true){
      $str .= '; httponly';
    }

    return $str;
  }

  public function getName(){
    return $this->name;
  }

  public function getValue(){
    return $this->value;
  }

  public function getDomain(){
    return $this->domain;
  }

  public function getExpiresTime(){
    return $this->expire;
  }

  public function getPath(){
    return $this->path;
  }

  public function isSecure(){
    return $this->secure;
  }

  public function isHttpOnly(){
    return $this->httpOnly;
  }

  public function isCleared(){
    return $this->expire < time();
  }
}
