<?php
namespace SCFViewer\Model;

class Sentence extends \AZ\Framework\ORM{
  public static $table = 'sentences';
  public static $key = 'id_sentence';
  public static $foreign_key = 'id_sentence';
  public static $order_by = array('id_sentence' => 'ASC');

  public static $has = array(
    'examples' => '\SCFViewer\Model\Example',
  );
  
}