<?php
namespace MVC\Controller;

use MVC\Library\Database;
use MVC\Library\ORM;
use MVC\Model\Verb;

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
    
    $verbs = Verb::fetch(array('frequency > 1'));
    $count = Verb::count(array('frequency > 1'));


    $return = array();
    $return['verbs'] = $verbs; 
    $return['count'] = $count; 
    return array('verb-list.html.php', $return);
  }

}
