<?php

namespace AZ\Framework;

class JSON{
  
  public static function encode($data){
    return json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
  }

  public static function decode($string){
    return json_decode($string, true);
  }

  public static function isValidIdentifier($identifier){
    $pattern = '/^[$_\p{L}][$_\p{L}\p{Mn}\p{Mc}\p{Nd}\p{Pc}\x{200C}\x{200D}]*+$/u';
    $parts = explode('.', $identifier);
    foreach ($parts as $part) {
      if (!preg_match($pattern, $part)) {
        return false;
      }
    }
    return true;
  }
}

?>