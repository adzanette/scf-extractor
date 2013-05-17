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

class Example extends \MVC\Library\ORM{
  public static $table = 'examples';
  public static $key = 'id_example';
  public static $foreign_key = 'id_example';
  public static $order_by = array('id_example' => 'ASC');
 
  public static $belongs_to = array(
    'sentence' => '\MVC\Model\Sentence',
    'frame' => '\MVC\Model\Frame',
    'semanticFrame' => '\MVC\Model\SemanticFrame'
  );

  public static $has = array(
    'arguments' =>  '\MVC\Model\Argument'
  );

}