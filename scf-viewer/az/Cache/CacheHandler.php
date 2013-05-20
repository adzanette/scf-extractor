<?php

namespace AZ\Framework\Cache;
use \Memcache as MemcacheDriver;
use \Memcached as MemcachedDriver;

class CacheHandler {

  private $cacheType;
  private $cacheDriver;
  private $defaultTimeout;
  private $enabled;  
  
  public function __construct($params){
    $this->defaultTimeout = $params['default-timeout'];
    $this->cacheType = $params['type'];
    $this->enabled = $params['enabled'];
    switch ($this->cacheType) {
      case 'memcache':
        $host = $params['host'];
        $port = $params['port'];
        if($this->enabled) {
          $memcache = new MemcacheDriver();
          if($memcache->connect($host, $port)) {
            $this->cacheDriver = new Memcache();
            $this->cacheDriver->setMemcache($memcache);
          }else{
            $this->enabled = false;
          }  
        }  
        break;
      case 'memcached':
        $servers = $params['servers'];
        if($this->enabled) {
          $memcached = new MemcachedDriver();
          if($memcached->addServers($servers)) {
            $this->cacheDriver = new Memcached();
            $this->cacheDriver->setMemcached($memcached);
          }else{
            $this->enabled = false;
          }  
        }  
        break;
      case 'apc':
        $this->cacheDriver = new APC();
        break;
      case 'array':
        $this->cacheDriver = new ArrayCache();
        break;
      default:
        throw new \InvalidArgumentException(sprintf('"%s" is an unrecognized cache driver.', $type));
        break;
    }
  }
  
  public function fetch($id){
    if($this->enabled){
      return $this->cacheDriver->fetch($id);
    }else{
      return false;
    }  
  }

  public function contains($id){
    if($this->enabled){
      return  $this->cacheDriver->contains($id);
    }else{
      return false;
    }              
  }

  public function save($id, $data, $lifeTime = -1){
    if($this->enabled){
      if ($lifeTime == -1) $lifetime = $this->defaultTimeout;
      return $this->cacheDriver->save($id, $data, $lifeTime);
    }else{
      return true;
    }        
  }

  public function delete($id){
    if($this->enabled){
      return $this->cacheDriver->delete($id);
    }else{
      return true;
    }  
  }

  public function flush(){
    if($this->enabled){
      return $this->cacheDriver->flush();
    }else{
      return true;
    }  
  }
  
}