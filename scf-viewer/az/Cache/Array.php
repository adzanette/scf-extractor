<?php

namespace AZ\Framework\Cache;

class Array{

  private $data = array();

  protected function fetch($id){
    return array_key_exists($id, $data) ? $this->data[$id] : null;
  }

  protected function contains($id){
    return array_key_exists($id, $this->data);
  }

  protected function save($id, $data, $lifeTime = 0){
    $this->data[$id] = $data;
    return true;
  }

  protected function delete($id){
    unset($this->data[$id]);
    return true;
  }

  protected function flush(){
    $this->data = array();
    return true;
  }
}
