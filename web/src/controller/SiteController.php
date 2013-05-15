<?php
namespace MVC\Controller;

use MVC\Library\Database;
use MVC\Library\ORM;
use MVC\Model\Verb;
use MVC\Model\Frame;
use MVC\Model\Example;

class SiteController extends \MVC\Library\Controller{
  
  public function initialize($params) {
    if (array_key_exists('corpus', $params)){
      $databaseConfig = $this->context->settings->get('database');
      $databaseConfig['dbname'] = $params['corpus'];
      $database = new Database($databaseConfig);
      ORM::$db = $database;
    }
  }

  public function index(){
    $settings = $this->context->settings;

    $databases = $settings->get('databases');

    $return = array();
    $return['databases'] = $databases;
    return array('index.html.php', $return);
  }

  public function showVerbList($corpus, $page){
      
    $settings = $this->context->settings;

    $limit = $settings->get('template/page-size');
    $offset = $page * $limit;
    $filter = array('frequency > 1');

    $verbs = Verb::fetch($filter, $limit, $offset);
    $count = Verb::count($filter);

    $return = array();
    $return['verbs'] = $verbs; 
    $return['count'] = $count; 
    $return['corpus'] = $corpus; 
    $return['page'] = $page; 
    return array('verb-list.html.php', $return);
  }

  public function showFrameList($corpus, $verbId, $verbPage, $page){
      
    $settings = $this->context->settings;

    $verb = Verb::fetch(array('id_verb = '.$verbId));

    $limit = $settings->get('template/page-size');
    $offset = $page * $limit;
    $filter = array('id_verb = '.$verbId,'frequency > 1');

    $frames = Frame::fetch($filter, $limit, $offset);
    $count = Frame::count($filter);

    $return = array();
    $return['verb'] = $verb; 
    $return['frames'] = $frames; 
    $return['count'] = $count; 
    $return['corpus'] = $corpus; 
    $return['verbPage'] = $verbPage; 
    $return['page'] = $page; 
    return array('frame-list.html.php', $return);
  }

  public function showExampleList($corpus, $verbId, $verbPage, $frameId, $framePage, $page){
      
    $settings = $this->context->settings;

    $verb = Verb::fetch(array('id_verb = '.$verbId));
    $frame = Frame::fetch(array('id_frame = '.$frameId));

    $limit = $settings->get('template/page-size');
    $offset = $page * $limit;
    $filter = array('id_verb = '.$verbId, 'id_frame = '.$frameId,'frequency > 1');

    $examples = Example::fetch($filter, $limit, $offset);
    $count = Example::count($filter);

    $return = array();
    $return['verb'] = $verb; 
    $return['frame'] = $frame; 
    $return['examples'] = $examples; 
    $return['count'] = $count; 
    $return['corpus'] = $corpus; 
    $return['verbPage'] = $verbPage; 
    $return['framePage'] = $verbPage; 
    $return['page'] = $page; 
    return array('example-list.html.php', $return);
  }

}
