<?php
namespace SCFViewer\Model;

class Frame extends \AZ\Framework\ORM{
  public static $table = 'frames';
  public static $key = 'id_frame';
  public static $foreign_key = 'id_frame';
  public static $order_by = array('frequency' => 'DESC');
 
  public static $belongs_to = array(
    'verb' => '\SCFViewer\Model\Verb',
  );

  public static $has = array(
    'examples' => '\SCFViewer\Model\Example'
  );

}