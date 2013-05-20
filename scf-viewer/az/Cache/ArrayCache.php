<?php

namespace AZ\Framework\Cache;

class ArrayCache{

  private $data = array();

  public function fetch($id){
    return array_key_exists($id, $this->data) ? $this->data[$id] : null;
  }

  public function contains($id){
    return array_key_exists($id, $this->data);
  }

  public function save($id, $data, $lifeTime = 0){
    $this->data[$id] = $data;
    return true;
  }

  public function delete($id){
    unset($this->data[$id]);
    return true;
  }

  public function flush(){
    $this->data = array();
    return true;
  }
}
