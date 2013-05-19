<?php
namespace SCFViewer\Model;

class Verb extends \AZ\Framework\ORM{
  public static $table = 'verbs';
  public static $foreign_key = 'id_verb';
  public static $key = 'id_verb';
  public static $order_by = array('frequency' => 'DESC');

   public static $has = array(
    'frames'  => '\SCFViewer\Model\Frame',
    'semanticFrames' => '\SCFViewer\Model\SemanticFrame'
  );

}