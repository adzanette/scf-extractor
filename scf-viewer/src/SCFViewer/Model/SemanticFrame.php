<?php
namespace SCFViewer\Model;

class SemanticFrame extends \AZ\Framework\ORM{
  public static $table = 'semantic_frames';
  public static $key = 'id_frame';
  public static $foreign_key = 'id_semantic_frame';
  public static $order_by = array('frequency' => 'DESC');

  public static $belongs_to = array(
    'verb' => '\MVC\Model\Verb',
  );

  public static $has = array(
    'examples' => '\MVC\Model\Example'
  );

}