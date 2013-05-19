<?php

namespace AZ\Framework;

class JSON{
  
  public static function encode($data){
    return json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
  }

  public static function decode($string){
    return json_decode($string, true);
  }

}

?>