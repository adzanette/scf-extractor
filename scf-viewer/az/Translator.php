<?php

namespace AZ\Framework;

class Translator{
  private $folder;
  private $globalDomain;
  private $defaultLocale;
  private $translations;

  public function __construct($folder, $globalDomain, $locale){
    $this->folder = $folder;
    $this->globalDomain = $globalDomain;
    $this->defaultLocale = $locale;
    $this->translations = array();
    $this->loadTranslations($this->globalDomain, $this->defaultLocale);
  }

  private function loadTranslations($domain, $locale){
    $file = new File($this->folder.$domain.'.'.$locale.'.json');
    
    if (!array_key_exists($locale, $this->translations)){
      $this->translations[$locale] = array();
    }

    if (!array_key_exists($domain, $this->translations[$locale]) && $file->exists()){
      $this->translations[$locale][$domain] = JSON::decode($file->get());
    }
  }

  public function translate($messageId, $params = null, $domain = null, $locale = null){
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