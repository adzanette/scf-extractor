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

class Verb extends \MVC\Modules\ORM{
  public static $table = 'verbs';
  //public static $foreign_key = 'id_verb';
  public static $key = 'id_verb';


}