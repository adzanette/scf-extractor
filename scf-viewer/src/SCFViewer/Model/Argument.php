<?php

namespace SCFViewer\Model;

class Argument extends \AZ\Framework\ORM{
  public static $table = 'arguments';
  public static $key = 'id_argument';
  public static $foreign_key = 'id_argument';
  public static $order_by = array('id_argument' => 'ASC');

  public static $belongs_to = array(
    'example' => '\SCFViewer\Model\Example',
  );
  
}