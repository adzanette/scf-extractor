<?php

namespace AZ\Framework\Cache;

use \Memcached;

class Memcached{

  private $memcached;

  public function setMemcached(Memcached $memcached){
    $this->memcached = $memcached;
  }

  public function getMemcached(){
    return $this->memcached;
  }

  protected function fetch($id){
    return $this->memcached->get($id);
  }

  protected function contains($id){
    return (false !== $this->memcached->get($id));
  }

  protected function save($id, $data, $lifeTime = 0){
    if ($lifeTime > 30 * 24 * 3600) {
      $lifeTime = time() + $lifeTime;
    }
    return $this->memcached->set($id, $data, (int) $lifeTime);
  }

  protected function delete($id){
    return $this->memcached->delete($id);
  }

  protected function flush(){
    return $this->memcached->flush();
  }
}
