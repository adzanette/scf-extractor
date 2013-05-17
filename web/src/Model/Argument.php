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

class Argument extends \MVC\Library\ORM{
  public static $table = 'arguments';
  public static $key = 'id_argument';
  public static $foreign_key = 'id_argument';
  public static $order_by = array('id_argument' => 'ASC');

  public static $belongs_to = array(
    'example' => '\MVC\Model\Example',
  );
  
}