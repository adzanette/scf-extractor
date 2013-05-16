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
    return $this->render('index.html.php', $return); 
  }

  public function showVerbList($corpus, $page){
      
    $settings = $this->context->settings;

    $limit = $settings->get('template/page-size');
    $offset = ($page-1) * $limit;
    $filter = array('frequency > 1');

    $verbs = Verb::fetch($filter, $limit, $offset, array('frequency' => 'DESC'));
    $count = Verb::count($filter);

    $return = array();
    $return['verbs'] = $verbs; 
    $return['count'] = $count; 
    $return['corpus'] = $corpus; 
    $return['page'] = $page; 
    return $this->render('verb-list.html.php', $return);
  }

  public function showFrameList($corpus, $verbId, $verbPage, $page){
    $settings = $this->context->settings;

    $verb = Verb::row(array('id_verb = '.$verbId));
    
    $limit = $settings->get('template/page-size');
    $offset = ($page-1) * $limit;
    $filter = array('id_verb = '.$verbId);

    $frames = Frame::fetch($filter, $limit, $offset, array('frequency' => 'DESC'));
    $count = Frame::count($filter);
  
    $return = array();
    $return['verb'] = $verb; 
    $return['frames'] = $frames; 
    $return['count'] = $count; 
    $return['corpus'] = $corpus; 
    $return['verbPage'] = $verbPage; 
    $return['page'] = $page; 
    return $this->render('frame-list.html.php', $return);
  }

  public function showExampleList($corpus, $verbId, $verbPage, $frameId, $framePage, $page){
      
    $settings = $this->context->settings;

    $frame = Frame::row(array('id_frame = '.$frameId));

    $limit = $settings->get('template/page-size');
    $offset = $page * $limit;
    $filter = array('id_frame = '.$frameId);

    $examples = Example::fetch($filter, $limit, $offset, array('id_example' => 'DESC'));
    $count = Example::count($filter);

    $return = array();
    $return['frame'] = $frame; 
    $return['examples'] = $examples; 
    $return['count'] = $count; 
    $return['corpus'] = $corpus; 
    $return['verbPage'] = $verbPage; 
    $return['framePage'] = $framePage; 
    $return['page'] = $page; 
    return $this->render('example-list.html.php', $return);
  }

}
