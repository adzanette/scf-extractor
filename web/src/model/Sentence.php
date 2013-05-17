<?php
/**
 * Car Model
 *
 * @package   MicroMVC
 * @author    David Pennington
 * @copyright (c) 2011 MicroMVC Framework
 * @license   http://micromvc.com/license
 ********************************** 80 Columns *********************************
 */
namespace MVC\Model;

class Sentence extends \MVC\Library\ORM{
  public static $table = 'sentences';
  public static $key = 'id_sentence';
  public static $foreign_key = 'id_sentence';
  public static $order_by = array('id_sentence' => 'ASC');

  public static $has = array(
    'examples' => '\MVC\Model\Example',
  );
  
}