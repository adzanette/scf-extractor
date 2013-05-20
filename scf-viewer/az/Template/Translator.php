<?php
namespace AZ\Framework\Template;

use AZ\Framework\File;
use AZ\Framework\JSON;

class Translator{
  private $folder;
  private $globalDomain;
  private $defaultLocale;
  private $translations;
  private $cache;

  public function __construct($folder, $globalDomain, $locale, $cache){
    $this->folder = $folder;
    $this->globalDomain = $globalDomain;
    $this->defaultLocale = $locale;
    $this->translations = array();
    $this->cache = $cache;
    $this->loadTranslations($this->globalDomain, $this->defaultLocale);
  }

  private function loadTranslations($domain, $locale){
    $file = new File($this->folder.$domain.'.'.$locale.'.json');
    
    if ($this->cache->contains($domain.'.'.$locale))
      return true;

    if (!array_key_exists($locale, $this->translations)){
      $this->translations[$locale] = array();
    }

    if (!array_key_exists($domain, $this->translations[$locale]) && $file->exists()){
      $this->translations[$locale][$domain] = JSON::decode($file->get());
      foreach($this->translations[$locale][$domain] as $id => $message){
        $this->cache->save($domain.'.'.$locale.'.'.$id, $message);
      }
      $this->cache->save($domain.'.'.$locale, true);
    }
  }

  public function translate($messageId, $params = null, $domain = null, $locale = null){
    if ($this->cache->contains($domain.'.'.$locale.'.'.$messageId))
      return $this->replace($this->cache->fetch($domain.'.'.$locale.'.'.$messageId), $params);

    if (is_null($locale)) $locale = $this->defaultLocale;
    if (is_null($domain)) $domain = $this->globalDomain;
    $this->loadTranslations($domain, $locale);
    
    if (!array_key_exists($messageId, $this->translations[$locale][$domain])){
      return $messageId; 
    }else{
      $message = $this->translations[$locale][$domain][$messageId];
      return $this->replace($message, $params);
    }
  }

  public function replace($message, $params){
    if (is_array($params) && count($params) > 0){
      foreach ($params as $key => $value){
        $message = str_replace('{'.$key.'}', $value, $message);
      }
    }

    return $message;
  }
}

?>