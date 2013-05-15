<?php

namespace MVC\Library;

class Translator{
  private $folder;
  private $globalDomain;
  private $defaultLocale;
  private $fileHandler;
  private $translations;

  public function __construct($folder, $globalDomain, $locale){
    $this->folder = $folder;
    $this->globalDomain = $globalDomain;
    $this->defaultLocale = $locale;
    $this->fileHandler = new FileHandler();
    $this->translations = array();
    $this->loadTranslations($this->globalDomain, $this->defaultLocale);
  }

  private function loadTranslations($domain, $locale){
    $file = $this->folder.'/'.$domain.'.'.$locale.'.json';
    
    if (!array_key_exists($locale, $this->translations)){
      $this->translations[$locale] = array();
    }

    if (!array_key_exists($domain, $this->translations[$locale]) && $this->fileHandler->exists($file)){
      $this->translations[$locale][$domain] = json_decode(file_get_contents($file), true);
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
    if (is_null($params)) return $message;
    foreach ($params as $key => $value){
      $message = str_replace('%'.$key.'%', $value, $message);
    }
    return $message;
  }
}

?>