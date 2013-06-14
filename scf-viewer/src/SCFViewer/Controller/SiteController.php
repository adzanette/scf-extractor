<?php
namespace SCFViewer\Controller;

use AZ\Framework\Database;
use AZ\Framework\ORM;
use AZ\Framework\HTTP\Response;
use AZ\Framework\HTTP\JsonResponse;
use SCFViewer\Model\Verb;
use SCFViewer\Model\Frame;
use SCFViewer\Model\SemanticFrame;
use SCFViewer\Model\Example;
use SCFViewer\Model\Argument;

class SiteController extends \AZ\Framework\Kernel\Controller{
  
  public function initialize($params) {
    if (array_key_exists('corpus', $params)){
      $databaseConfig = $this->context->settings->get('database');
      $databaseConfig['dbname'] = $params['corpus'];
      $this->set('database', new Database($databaseConfig));
    }
  }

  public function index(){
    $settings = $this->context->settings;

    $databases = $settings->get('databases');

    $return = array();
    $return['databases'] = $databases;
    return $this->render('select-corpus.html.php', $return); 
  }

  public function show404(){
    $html = '<pre>';
    $html .= print_r($this->context, true);
    return new Response($html); 
  }

  public function selectCorpus(){
    $post = $this->get('request')->post;
    $router = $this->get('router');
    
    $corpus = $post->get('corpus');
    $url = $router->generate('verb-list', array('corpus' => $corpus, 'page' => 1));

    return $this->redirect($url); 
  }

  public function showVerbList($corpus, $page){
      
    $settings = $this->context->settings;

    $limit = $settings->get('template/page-size');
    $offset = ($page-1) * $limit;
    $filter = array('frequency > 1');

    $verbs = Verb::fetch($filter, $limit, $offset);
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
    $offset = ($page-1) * $limit;
    $filter = array('id_frame = '.$frameId, 'active = 1');

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
    $return['offset'] = $offset;
    $return['roles'] = $settings->get('roles');
    return $this->render('example-list.html.php', $return);
  }

  public function saveArgument($corpus){
    $post = $this->get('request')->post;
    $argumentId = $post->getInt('id_argument');
    $syntax = $post->getAlnum('syntax');
    $semantic = $post->get('semantic');

    $filter = array('id_argument = '.$argumentId);

    try{
      $argument = Argument::row($filter);
      $argument->sintax = $syntax;
      $argument->semantic = $semantic;

      $argument->save();
      $success = true;
    }catch(Exception $e){
      $success = false;
    }

    $return['success'] = $success;
    return new JsonResponse($return);
  }

  public function deleteArgument($corpus){
    $post = $this->get('request')->post;
    $argumentId = $post->getInt('id_argument');
   
    $filter = array('id_argument = '.$argumentId);
   
    try{
      $argument = Argument::row($filter);
      $argument->active = 0;
      $argument->save();
      $success = true;
    }catch(Exception $e){
      $success = false;
    }

    $return['success'] = $success;
    return new JsonResponse($return);
  }

  public function deleteExample($corpus){
    $post = $this->get('request')->post;
    $exampleId = $post->getInt('id_example');
   
    $filter = array('id_example = '.$exampleId);
   
    try{
      $argument = Example::row($filter);
      $argument->active = 0;
      $argument->save();
      $success = true;
    }catch(Exception $e){
      $success = false;
    }

    $return['success'] = $success;
    return new JsonResponse($return);
  }

  public function showSemanticFramesListByVerb($corpus, $verbId, $verbPage, $page){
    $settings = $this->context->settings;

    $verb = Verb::row(array('id_verb = '.$verbId));
    
    $limit = $settings->get('template/page-size');
    $offset = ($page-1) * $limit;
    $filter = array('id_verb = '.$verbId);

    $frames = SemanticFrame::fetch($filter, $limit, $offset, array('frequency' => 'DESC'));
    $count = SemanticFrame::count($filter);
  
    $return = array();
    $return['verb'] = $verb; 
    $return['frames'] = $frames; 
    $return['count'] = $count; 
    $return['corpus'] = $corpus; 
    $return['verbPage'] = $verbPage; 
    $return['page'] = $page; 
    return $this->render('semantic-frames.html.php', $return);
  }


  public function showSemanticFramesList($corpus, $page){   
    $settings = $this->get('settings');
    $database = SemanticFrame::$db;

    $limit = $settings->get('template/page-size');
    $offset = ($page-1) * $limit;
    
    list($sql, $params) = $database->select('frame, SUM(frequency) AS count', SemanticFrame::$table, null, $limit, $offset, array('count' => 'DESC'), array('frame'));
    $semanticFrames = $database->fetch($sql, $params);
    
    $count = $database->column("SELECT count(*) as total FROM (SELECT DISTINCT(frame) FROM semantic_frames) as frames;");
   
    $return = array();
    $return['frames'] = $semanticFrames; 
    $return['count'] = $count; 
    $return['corpus'] = $corpus; 
    $return['page'] = $page; 
    return $this->render('semantic-frames-list.html.php', $return);
  }

  public function showVerbSemanticFramesList($corpus, $framePage, $frame){
    $database = Verb::$db;  
    
    $query = "SELECT v.*, 
            (select frequency 
              from semantic_frames as sf 
              where frame = '".$frame."' 
              and sf.id_verb = v.id_verb) 
              as frequency
          FROM verbs as v
          WHERE id_verb in (
            SELECT id_verb
            FROM semantic_frames
            WHERE frame = '".$frame."'
          ) order by frequency desc;";
    $statement = $database->query($query);
    $verbs = $statement->fetchAll(\PDO::FETCH_OBJ);
    
    $return = array();
    $return['verbs'] = $verbs; 
    $return['corpus'] = $corpus; 
    $return['frame'] = $frame;
    $return['framePage'] = $framePage; 
    return $this->render('verb-semantic-frames-list.html.php', $return); 
  }

  public function showSemanticFrameExamples($corpus, $framePage, $frame, $verbId){
    $database = Example::$db;  

    $verb = Verb::row(array('id_verb = '.$verbId));
    
    $query = "select sentences.* from sentences where id_sentence in (
              SELECT id_sentence 
              FROM examples
              WHERE id_semantic_frame = (
                SELECT id_frame
                FROM semantic_frames
                WHERE frame = '".$frame."' and id_verb = '".$verbId."'
              ));";
    $statement = $database->query($query);
    $sentences = $statement->fetchAll(\PDO::FETCH_OBJ);
    
    $return = array();
    $return['sentences'] = $sentences; 
    $return['corpus'] = $corpus; 
    $return['frame'] = $frame;
    $return['verb'] = $verb;
    $return['framePage'] = $framePage; 
    return $this->render('semantic-frame-examples.html.php', $return); 
  }
}
