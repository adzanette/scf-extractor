<?php

namespace AZ\Framework;

class RedirectResponse extends Response{
  protected $redirectUrl;

  public function __construct($url, $status = 302, $headers = array()){
    parent::__construct('', $status, $headers);
    $this->setRedirectUrl($url);
    if (!$this->isRedirect()) {
      throw new \Exception('Invalid Redirect Code.');
    }
  }

  public static function create($url = '', $status = 302, $headers = array()){
    return new static($url, $status, $headers);
  }

  public function getRedirectUrl(){
    return $this->redirectUrl;
  }

  public function setRedirectUrl($url){
    $this->redirectUrl = $url;
    $this->headers->set('Location', $url);
    return $this;
  }
}
