<?php
namespace SCFViewer\Model;

class Example extends \AZ\Framework\ORM{
  public static $table = 'examples';
  public static $key = 'id_example';
  public static $foreign_key = 'id_example';
  public static $order_by = array('id_example' => 'ASC');
 
  public static $belongs_to = array(
    'sentence' => '\SCFViewer\Model\Sentence',
    'frame' => '\SCFViewer\Model\Frame',
    'semanticFrame' => '\SCFViewer\Model\SemanticFrame'
  );

  public static $has = array(
    'arguments' =>  '\SCFViewer\Model\Argument'
  );

}