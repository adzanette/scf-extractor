<?php
namespace AZ\Framework\HTTP;

use AZ\Framework\JSON;

class JsonResponse extends Response{
  protected $data;
  protected $callback;

  public function __construct($data = null, $status = 200, $headers = array()){
    parent::__construct('', $status, $headers);

    if ($data === null){
      $data = array();
    }
    $this->setData($data);
  }

  public static function create($data = null, $status = 200, $headers = array()){
    return new static($data, $status, $headers);
  }

  public function setCallback($callback = null){
    if ($callback !== null)
      if (!JSON::isValidIdentifier($callback))
        throw new \InvalidArgumentException('The callback name is not valid.');

    $this->callback = $callback;
    return $this->update();
  }

  public function setData($data = array()){
    $this->data = JSON::encode($data);
    return $this->update();
  }

  protected function update(){
      if ($this->callback !== null){
        $this->headers->set('Content-Type', 'text/javascript');
        return $this->setContent(sprintf('%s(%s);', $this->callback, $this->data));
      }

      if (!$this->headers->has('Content-Type') || $this->headers->get('Content-Type') === 'text/javascript') {
        $this->headers->set('Content-Type', 'application/json');
      }

      return $this->setContent($this->data);
  }
}
