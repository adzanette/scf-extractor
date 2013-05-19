<?php
namespace AZ\Framework\Cache;

class APC{

  protected function fetch($id){
    return apc_fetch($id);
  }

  protected function contains($id){
    return apc_exists($id);
  }

  protected function save($id, $data, $lifeTime = 0){
    return (bool) apc_store($id, $data, (int) $lifeTime);
  }

  protected function delete($id){
    return apc_delete($id);
  }

  protected function flush(){
    return apc_clear_cache() && apc_clear_cache('user');
  }
}
