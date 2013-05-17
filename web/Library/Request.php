<?php

namespace MVC\Library;

class Request{
  
  public $post;
  public $query;
  public $server;
  public $method;
  public $headers;
  public $session;
  
  public function __construct(array $query = array(), array $post = array(), array $server = array()){
    $this->initialize($query, $post, $server);
  }

  public function initialize(array $query = array(), array $post = array(), array $server = array()){
    $this->post = new ParameterBag($post);
    $this->query = new ParameterBag($query);
    $this->server = new ParameterBag($server);
    $this->headers = new ParameterBag($this->getHeaders());

    $this->method = null;
  }

  public static function createFromGlobals(){
    $request = new static($_GET, $_POST, $_SERVER);

    if (0 === strpos($request->headers->get('CONTENT_TYPE'), 'application/x-www-form-urlencoded')
      && in_array(strtoupper($request->server->get('REQUEST_METHOD', 'GET')), array('PUT', 'DELETE', 'PATCH'))) {
      parse_str($request->getContent(), $data);
        $request->post = new ParameterBag($data);
    }

    return $request;
  }

  public function getSession(){
    return $this->session;
  }

  public function setSession(Session $session){
    $this->session = $session;
  }

  public function getClientIP(){
    if(!$this->server->has('HTTP_CLIENT_IP')){
      $ip = $this->server->get('HTTP_CLIENT_IP');
    }elseif (!$this->server->has('HTTP_X_FORWARDED_FOR')){
      $ip = $this->server->get('HTTP_X_FORWARDED_FOR');
    }else{
      $ip = $this->server->get('REMOTE_ADDR');
    }
    return $ip;
  }

  public function getMethod(){
    if ($this->method === null) {
      $this->method = strtoupper($this->server->get('REQUEST_METHOD', 'GET'));
      if ($this->method === 'POST') {
        if ($method = $this->headers->get('X-HTTP-METHOD-OVERRIDE')) {
          $this->method = strtoupper($method);
        }
      }
    }
    return $this->method;
  }

  public function isXmlHttpRequest(){
    return $this->headers->get('X-Requested-With') == 'XMLHttpRequest';
  }

  public function getHeaders(){
    $headers = array();
    foreach ($this->server as $key => $value) {
      if (0 === strpos($key, 'HTTP_')) {
        $headers[substr($key, 5)] = $value;
      }elseif (in_array($key, array('CONTENT_LENGTH', 'CONTENT_MD5', 'CONTENT_TYPE'))) {
        $headers[$key] = $value;
      }
    }

    if (!$this->server->has('PHP_AUTH_USER')) {
      $headers['PHP_AUTH_USER'] = $this->server->get('PHP_AUTH_USER');
      $headers['PHP_AUTH_PW'] = !$this->server->has('PHP_AUTH_PW') ? $this->server->get('PHP_AUTH_PW') : '';
    }else{
      $authorizationHeader = null;
      if (isset($this->server['HTTP_AUTHORIZATION'])) {
        $authorizationHeader = $this->server['HTTP_AUTHORIZATION'];
      }elseif (isset($this->server['REDIRECT_HTTP_AUTHORIZATION'])) {
        $authorizationHeader = $this->server['REDIRECT_HTTP_AUTHORIZATION'];
      }

      if ((null !== $authorizationHeader) && (0 === stripos($authorizationHeader, 'basic'))){
        $exploded = explode(':', base64_decode(substr($authorizationHeader, 6)));
        if (count($exploded) == 2){
          list($headers['PHP_AUTH_USER'], $headers['PHP_AUTH_PW']) = $exploded;
        }
      }
    }

    if (isset($headers['PHP_AUTH_USER'])) {
      $headers['AUTHORIZATION'] = 'Basic '.base64_encode($headers['PHP_AUTH_USER'].':'.$headers['PHP_AUTH_PW']);
    }

    return $headers;
  }

}


?>