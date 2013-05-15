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

class Frame extends \MVC\Library\ORM{
  public static $table = 'frames';
  public static $key = 'id_frame';
  public static $foreign_key = 'id_frame';
 
  public static $belongs_to = array(
    'verb' => '\MVC\Model\Verb',
  );

  public static $has = array(
    'examples' => '\MVC\Model\Example'
  );

}