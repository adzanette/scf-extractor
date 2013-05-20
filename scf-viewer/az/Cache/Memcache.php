<?php

namespace AZ\Framework\Cache;

use \Memcache;

class MemcacheCache{
  
  private $memcache;

  public function setMemcache(Memcache $memcache){
    $this->memcache = $memcache;
  }

  public function getMemcache(){
    return $this->memcache;
  }

  public function fetch($id){
    return $this->memcache->get($id);
  }

  public function contains($id){
    return (bool) $this->memcache->get($id);
  }

  public function save($id, $data, $lifeTime = 0){
    if ($lifeTime > 30 * 24 * 3600) {
        $lifeTime = time() + $lifeTime;
    }
    return $this->memcache->set($id, $data, 0, (int) $lifeTime);
  }

  public function delete($id){
    return $this->memcache->delete($id);
  }

  public function flush(){
    return $this->memcache->flush();
  }
}
