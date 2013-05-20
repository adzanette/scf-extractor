<?php
namespace AZ\Framework\Cache;

class APC{

  public function fetch($id){
    return apc_fetch($id);
  }

  public function contains($id){
    return apc_exists($id);
  }

  public function save($id, $data, $lifeTime = 0){
    return (bool) apc_store($id, $data, (int) $lifeTime);
  }

  public function delete($id){
    return apc_delete($id);
  }

  public function flush(){
    return apc_clear_cache() && apc_clear_cache('user');
  }
}
