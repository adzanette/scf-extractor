<?php

namespace MVC\Library;

/**
*  Service to control the rendering of the page.
*
*  @package service
*  @category core
*  @author Adriano Zanette <zanette@intelimen.com.br>
*  @version 1.0
*/
class Template {
 
  /**
   * @var array Javascript File.
  */
  protected $js = array();
  
  /**
   * @var array Javascript Text Code.
  */
  protected $jsCode = array();
  
  /**
   * @var array Css File.
  */
  protected $css = array();
  
  /**
   * @var array Css Rules.
  */
  protected $cssRules = array();
  
  /**
   * @var array MetaTags included in the page.
  */
  protected $metaTags = array(
    "name" => array(),
    "http-equiv" => array(),
    "og" => array()
  );
  
  /**
   * @var bool Sets whether to show the tags for Facebook og.
  */
  protected $showOgMetaTags = false;
  
  /**
   * @var string HTML Content Page.
  */
  protected $content;
  
  /**
   * @var string Identifier of the message of the title page.
  */
  protected $title;
  
  /**
   * @var string messages locale.
  */
  protected $locale;
  
  /**
   * @var string Identifier of the message of the title page if not defined $title.
  */
  protected $defaultTitle;
  
  /**
   * @var string Locale for messages if $locale is not set.
  */
  protected $defaultLocale;
  
  /**
   * @var string Domain for global messages.
  */
  protected $globalDomainMessages;
  
  /**
   * @var string Domain for page messages.
  */
  protected $pageDomainMessages;
  
  /**
   * @var  Translator message translator.
  */
  protected $translator;

  /**
   * @var  Router message translator.
  */
  protected $router;
  
  /**
   * @var  string Path to the Javascript, CSS and images.
  */
  protected $mediaUrl;
  
  /**
   * @var  string Path to the Javascript.
  */
  protected $jsUrl;
  
  /**
   * @var  string Path to CSS.
  */
  protected $cssUrl;
  
  /**
   * @var  string Path to the still images.
  */
  protected $imgUrl;
  
  /**
   * @var  string Path to the image content.
  */
  protected $uploadUrl;
  
  /**
   * @var  string Page Theme.
  */
  protected $theme;
    
  /**
  * @var bool Hide header
  */ 
  protected $hideHeaderContent;

  /**
  * @var bool Hide footer
  */ 
  protected $hideFooterContent;

  /**
  * @var array Messages to be dumped as js var
  */ 
  protected $jsTranslatedVar;
  
  /**
  * @var string Class to contextualize at body tag
  */ 
  protected $contextualize = '';  
  
  /**
  * @var string version of the static files
  */  
  protected $staticFilesVersion;
  
  public function __construct($mediaUrl, $jsUrl, $cssUrl, $imgUrl, $version, $title, $locale){
    global $appset;

    $this->mediaUrl = $mediaUrl;
    $this->jsUrl = $jsUrl;
    $this->cssUrl = $cssUrl;
    $this->imgUrl = $imgUrl;
    
    $this->staticFilesVersion = $version;
    
    $this->defaultTitle = $title;
    $this->defaultLocale = $locale;
    
    $this->addMetaTagHttpEquiv('X-UA-Compatible', 'IE=edge,chrome=1');
    //$this->addCss('jquery-ui-1.8.16.custom.css');
    //$this->addJs('feedback.js');
  }
  
  /**
  * Generates the final HTML page.
  *
  * @author Adriano Zanette <zanette@intelimen.com.br>
  * @version 1.0
  * @return string Returns the HTML of the page.
  */
  public function getPage(){
    ob_end_clean();
    ob_start();          
?>
<!DOCTYPE html>
<html class="no-js" lang="<?php echo $this->getLocale(); ?>">
  <head>
    <meta charset="utf-8" />
    <title><?php echo $this->getTitle(); ?></title>      
    <?php 
    echo $this->getMetaTags();
    echo $this->getFavicon();
    echo $this->getCss();
    ?>
    <script src="<?php echo $this->jsUrl; ?>modernizr-1.7.min.js<?php echo '?'.$this->staticFilesVersion; ?>"></script>
  </head>
  <body class="<?php echo $this->getLocale() . ' ' . $this->getContext(); ?>">
    <div class="content">
      <div class="wrapper">
        <?php 
        echo $this->getHeader();
        ?>
        <div class="main">
          <?php 
          echo $this->getContent();
          ?>
        </div>
      </div>
      <?php 
      echo $this->getFooter();
      ?>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
    <script>window.jQuery || document.write("<script src='<?php echo $this->jsUrl; ?>jquery-1.6.1.min.js'>\x3C/script>")</script>
    <?php
    echo $this->getJsInclude();
    echo "\n";
    echo $this->getJsCode();
    echo "\n";    
    ?>   
  </body>
</html>
<?php
      $content = ob_get_contents();
      ob_end_clean();
      return $content;
  }

  /**
  * Gets the path to the images.
  *
  * @author Adriano Zanette <zanette@intelimen.com.br>
  * @version 1.0
  * @return string Returns the path to the images.
  */
  public function getImageUrl(){
    
    return $this->imgUrl;
  }
  
  /**
  * Gets the path for dynamic content.
  *
  * @author Adriano Zanette <zanette@intelimen.com.br>
  * @version 1.0
  * @return string Returns the path to dynamic content.
  */
  public function getUploadUrl(){
    
    return $this->uploadUrl;
  }
  
    
  /**
  * Sets the contents of the body of the page.
  *
  * @author Adriano Zanette <zanette@intelimen.com.br>
  * @version 1.0
  * @param string $v Contents of the body of the page.
  */
  public function setContent($v){
    
    $this->content = $v;
  }
  
  /**
  * Gets the body content of the page.
  *
  * @author Adriano Zanette <zanette@intelimen.com.br>
  * @version 1.0
  * @return string Returns the contents of the body of the page.
  */
  public function getContent(){
    
    return $this->content;
  }
  
  /**
  * Gets the favicon of the page.
  *
  * @author Adriano Zanette <zanette@intelimen.com.br>
  * @version 1.0
  * @return string Returns the favicon of the page.
  */
  public function getFavicon(){
    
    return "
    <link rel=\"shortcut icon\" href=\"".$this->mediaUrl."favicon.ico\">
    <link rel=\"apple-touch-icon\" href=\"".$this->mediaUrl."apple-touch-icon.png\">";
  }
 
  /**
  * Adds a CSS file to the page.
  *
  * @author Adriano Zanette <zanette@intelimen.com.br>
  * @version 1.0
  * @param string $css CSS file name.
  * @param string $external If the file path is complete.
  */
  public function addCss($css, $external = false){
  
    if($external === true){
      $this->css[] = $css;
    }else{
      $this->css[] = $this->cssUrl.$css.'?'.$this -> staticFilesVersion;
    }
  }
  
  /**
  * Adds a piece of CSS code to the head of the page.
  *
  * @author Adriano Zanette <zanette@intelimen.com.br>
  * @version 1.0
  * @param string $rules CSS rules to be added.
  */
  public function addTextCss($rules){
  
    $this->cssRules[] = $rules;
  }
  
  /**
  * Gets the CSS of the page.
  *
  * @author Adriano Zanette <zanette@intelimen.com.br>
  * @version 1.0
  * @return string Returns CSS of the page.
  */
  public function getCss(){
  
    $allCss = "";
  
    if(sizeof($this->css) > 0){
      foreach($this->css as $css){
        $allCss .= "\n    <link rel=\"stylesheet\" href=\"".$css."\">";
      }
    }
    
    if(sizeof($this->cssRules) > 0){
      $allCss .= "\n    <style>";
      foreach($this->cssRules as $css){
        $allCss .= "\n    ".$css;
      }
      $allCss .= "\n    </style>";
    }
    
    return $allCss;
  }
  
  /**
  * Adds a JS file to the page.
  *
  * @author Adriano Zanette <zanette@intelimen.com.br>
  * @version 1.0
  * @param string $js JS file name.
  * @param string $external If the path of the file is complete or not.
  */
  public function addJs($js, $external = false){
    
    if($external === true){
      $this->js[] = $js;
    }else{
      $this->js[] = $this->jsUrl.$js.'?'.$this -> staticFilesVersion;
    }
    
  }
  
  /**
  * Adds a piece of JS code to the head of the page.
  *
  * @author Adriano Zanette <zanette@intelimen.com.br>
  * @version 1.0
  * @param string $rules JS code to be added.
  */
  public function addTextJs($code){
    
    $this->jsCode[] = $code;
  }
  
  
  /**
  * Gets the page's JS.
  *
  * @author Adriano Zanette <zanette@intelimen.com.br>
  * @version 1.0
  * @return string Returns the JS page.
  */
  public function getJs(){
  
    $allJs = "";
  
    if(sizeof($this->js) > 0){
      foreach($this->js as $js){
        $allJs .= "\n   <script src=\"".$js."\"></script>";
      }
    }
    
    if(sizeof($this->jsCode) > 0){
      $allJs .= "\n   <script>";
      foreach($this->jsCode as $js){
        $allJs .= "\n     ".$js;
      }
      $allJs .= "\n   </script>";
    }
    
    return $allJs;
  }
  
  /**
  * Gets the page's JS code to be printed.
  *
  * @author Mauricio Gon�alves Neto <goncalves@intelimen.com.br>
  * @version 1.0
  * @return string Returns the JS page.
  */
  public function getJsCode(){
  
    $allJs = "";
    
    if(sizeof($this->jsCode) > 0){
      $allJs .= "\n   <script>";
      foreach($this->jsCode as $js){
        $allJs .= "\n     ".$js;
      }
      $allJs .= "\n   </script>";
    }    
    return $allJs;
  }  
  
  /**
  * Gets the page's JS to be included.
  *
  * @author Mauricio Gon�alves Neto <goncalves@intelimen.com.br>
  * @version 1.0
  * @return string Returns the JS page.
  */
  public function getJsInclude(){
  
    $allJs = "";
  
    if(sizeof($this->js) > 0){
      foreach($this->js as $js){
        $allJs .= "\n   <script src=\"".$js."\"></script>";
      }
    }       
    return $allJs;
  }    
  
  /**
  * Adds a meta http-equiv type.
  *
  * @author Adriano Zanette <zanette@intelimen.com.br>
  * @version 1.0
  * @param string $httpEquiv Value of the attribute http-equiv meta tag.
  * @param string $content Value of the content attribute of meta tag.
  */
  public function addMetaTagHttpEquiv($httpEquiv, $content){
    
    $this->metaTags["http-equiv"][$httpEquiv] = $content;
  }
  
  /**
  * Adds a meta-type name.
  *
  * @author Adriano Zanette <zanette@intelimen.com.br>
  * @version 1.0
  * @param string $name Value of name attribute of the metatag.
  * @param string $content Value of the content attribute of meta tag.
  */
  public function addMetaTagName($name, $content){
    
    $this->metaTags["name"][$name] = $content;
  }
  
  /**
  * Adds a meta tag type og.
  *
  * @author Adriano Zanette <zanette@intelimen.com.br>
  * @version 1.0
  * @param string $name Value of name attribute of the metatag.
  * @param string $contentValue of the content attribute of meta tag.
  */
  public function addOgMetaTag($og, $content){
    $this->metaTags["og"][$og] = $content;
  }
  
  /**
  * Sets are displayed via the meta tags of the type og.
  *
  * @author Adriano Zanette <zanette@intelimen.com.br>
  * @version 1.0
  */
  public function showOgMetaTags(){
    
    $this->showOgMetaTags = true;
  }
  
  /**
  * Gets the meta tags of the page.
  *
  * @author Adriano Zanette <zanette@intelimen.com.br>
  * @version 1.0
  * @return string Returns the meta tags of the page.
  */
  public function getMetaTags(){
  
    $allMeta = "";
  
    if(sizeof($this->metaTags['http-equiv']) > 0){
      foreach($this->metaTags['http-equiv'] as $httpEquiv => $content){
        $allMeta .= "\n    <meta http-equiv=\"".$httpEquiv."\" content=\"".$content."\">";
      }
    }
    
    if(sizeof($this->metaTags['name']) > 0){
      foreach($this->metaTags['name'] as $name => $content){
        $allMeta .= "\n    <meta name=\"".$name."\" content=\"".$content."\">";
      }
    }
    
    if ($this->showOgMetaTags){
      if(sizeof($this->metaTags['og']) > 0){
        foreach($this->metaTags['og'] as $og => $content){
          $allMeta .= "\n    <meta property=\"".$og."\" content=\"".$content."\">";
        }
      }
    }
    return $allMeta;
  }
  
  /**
  * Sets the title of the page.
  *
  * @author Adriano Zanette <zanette@intelimen.com.br>
  * @version 1.0
  * @param string $v Page Title.
  */
  public function setTitle($v){
    
    $this->title = $v;
  }
  
  /**
  * Gets the Page Title.
  *
  * @author Adriano Zanette <zanette@intelimen.com.br>
  * @version 1.0
  * @return string returns Page Title.
  */
  public function getTitle(){
    
    if (isset($this->title) && $this->title != ''){
      $title = $this->title;
      return $this->translate($title);
    }else{
      $title = $this->defaultTitle;
      return $this->globalTranslate($title);
    }
  }
  
  /**
  * Sets page Locale.
  *
  * @author Adriano Zanette <zanette@intelimen.com.br>
  * @version 1.0
  * @param string $v page locale.
  */
  public function setLocale($v){
    
    $this->locale = $v;
  }
  
  /**
  * Gets code of the Page Locale.
  *
  * @author Adriano Zanette <zanette@intelimen.com.br>
  * @version 1.0
  * @return string returns the code of page locale.
  */
  public function getLocale(){
    
    $locale = $this->defaultLocale;
    if(isset($this->locale) && $this->locale != ""){
      $locale = $this->locale;
    }
    
    return $locale;
  }
   
  /**
  * Sets domain of the page messages.
  *
  * @author Adriano Zanette <zanette@intelimen.com.br>
  * @version 1.0
  * @param string $domain domain of page messages.
  */
  public function setPageDomainMessages($domain){
  
    $this->pageDomainMessages = $domain;
  }
  
  /**
  * Gets domain of pages messages.
  *
  * @author Adriano Zanette <zanette@intelimen.com.br>
  * @version 1.0
  * @return string returns domain of the page messages.
  */
  public function getPageDomainMessages(){
    
    return $this->pageDomainMessages;
  }
  
  /**
  * Set page translator.
  *
  * @author Adriano Zanette <zanette@intelimen.com.br>
  * @version 1.0
  * @param Symfony\Component\Translation\Translator $translator Translator of the messages of the page.
  */
  public function setTranslator($translator){
  
    $this->translator = $translator;
  }
  
  /**
  * Gets a message according to an ID.
  *
  * @author Adriano Zanette <zanette@intelimen.com.br>
  * @version 1.0
  * @param string $messageId Message identifier.
  * @param array $params Parameters for the message if necessary.
  * @return string Returns the text of a message.
  */
  public function translate($messageId, $params = array()){
       
    return $this->trans($messageId, $this->getPageDomainMessages(), $params);
  }
  
  /**
  * Sets the message according to a list of IDs to be dumped as js var.
  *
  * @author Mauricio Goncalves Neto <goncalves@intelimen.com.br>
  * @version 1.0
  * @param array $listMessageId list of Message identifier. Structure of array: key = messageId
  * @param array $params Parameters for the messages if necessary. Structure of array: key = messageId value = array of parameters
  * @return bool 
  */  
  public function translateList($listMessageId, $params = array()){
    $pageDomain = $this->getPageDomainMessages(); 
    if(is_array($listMessageId)) {
      foreach($listMessageId as $messageId => $translated) {
        if(!is_array($params[$messageId])) {
          $params[$messageId] = array();
        }
        $this->jsTranslatedVar[$messageId] = $this->trans($messageId, $pageDomain , $params[$messageId]);        
      }
    }       
    return true;
  }

  /**
  * Generates and set on addTextJs the js var of $jsTranslatedVar.
  *
  * @author Mauricio Goncalves Neto <goncalves@intelimen.com.br>
  * @version 1.0  
  * @return bool 
  */  
  public function generateJsTranslatedVar() {    
    if(is_array($this -> jsTranslatedVar) && count($this -> jsTranslatedVar) > 0) {
      $js = "
      if(!translation){
        var translation = ".json_encode($this -> jsTranslatedVar).";
      }else{
        $.extend(translation,".json_encode($this -> jsTranslatedVar).");
      }";
      $this->addTextJs($js);
    }  
    return true;
  }
  
  /**
  * Return the js var of $jsTranslatedVar.
  *
  * @author Igor Nascimento <nascimento@intelimen.com.br>
  * @version 1.0  
  * @return array Js array
  */  
  public function getJsTranslatedVarJs() {    
    if(is_array($this -> jsTranslatedVar) && count($this -> jsTranslatedVar) > 0) {
      $js = "<script type=\"text/javascript\">
      if(!translation){
        var translation = ".json_encode($this -> jsTranslatedVar).";
      }else{
        $.extend(translation,".json_encode($this -> jsTranslatedVar).");
      }
      </script>";
      return $js;
    }
  }
  
  /**
  * Gets a message according to a global ID.
  *
  * @author Adriano Zanette <zanette@intelimen.com.br>
  * @version 1.0
  * @param string $messageId message Id.
  * @param array $params Parameters for the message if necessary.
  * @return string Returns the text of a message.
  */
  public function globalTranslate($messageId, $params = array()){
    
    return $this->trans($messageId, $this->getGlobalDomainMessages(), $params);
  }
  
  /**
  * Obt�m uma mensagem de acordo com um ID.
  *
  * @author Adriano Zanette <zanette@intelimen.com.br>
  * @version 1.0
  * @param string $messageId message Id.
  * @param string $domain messages domain.
  * @param array $params Parameters for the message if necessary.
  * @return string Returns the text of a message.
  */
  private function trans($messageId, $domain, $params = array()){
    
    if (!isset($domain) || $domain == null || $domain == ""){
        $domain = "messages";
    }
    
    return  $this->translator->translate($messageId, $params, $domain, $this->getLocale());
  }
   

  public function setRouter($router){
    $this->router = $router;
  }

  /**
  * Generates a URL.
  *
  * @author Adriano Zanette <zanette@intelimen.com.br>
  * @version 1.0
  * @param string $name The route name
  * @param array $parameters An array of parameters
  * @param bool $absolute If the URL must be absolute
  *
  * @return string The generated URL
  */
  public function getLink($name, array $parameters = array(), $absolute = true){
  
    return $this->router->generate($name, $parameters, $absolute);
  }
  
  
  /**
  * Header.
  *
  * @author André Gonçalves <a.goncalves@intelimen.com.br>
  * @version 1.0
  * @return string The content header
  */
  public function getHeader() {
    if ($this->hideHeaderContent){
     return '';
    }
    ob_start();
    ?>
    
    <header id="main-header" class="clearfix">
    </header>
    
    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
  }
  
  /**
  * Hide header content
  *
  * @author Douglas Hipolito <hipolito@intelimen.com.br>
  * @version 1.0
  */
  public function hideHeader() {
    $this->hideHeaderContent = true;
  }
  
  /**
  * footer.
  *
  * @author Andr� Gon�alves <a.goncalves@intelimen.com.br>
  * @version 1.0
  * @return string The content footer
  */
  public function getFooter() {
    if ($this->hideFooterContent){
     return '';
    }
    ob_start();
    ?>    
    <footer id="main-footer">
    </footer>
    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
  }
  
  /**
  * Hide footer content
  *
  * @author Douglas Hipolito <hipolito@intelimen.com.br>
  * @version 1.0
  */
  public function hideFooter() {
    $this->hideFooterContent = true;
  }
  
  /**
  * Class to contextualize at body tag
  *
  * @author Igor Nascimento <nascimento@intelimen.com.br>
  * @version 1.0
  */
  public function setBodyContext($class){
    $this->contextualize = $class;
  }
  
  /**
  * Class to contextualize at body tag
  *
  * @author Igor Nascimento <nascimento@intelimen.com.br>
  * @version 1.0
  */
  public function getContext(){
    return $this->contextualize;
  } 

}