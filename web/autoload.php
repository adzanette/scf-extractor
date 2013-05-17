<?php
namespace MVC;
require_once __DIR__."/Library/Autoloader.php";
use MVC\Library\Autoloader;

$loader = new Autoloader();
$loader->registerNamespaces(array(
  'MVC\\Library'           => __DIR__.'/Library',
  'MVC\\Controller'           => __DIR__.'/src/Controller',
  'MVC\\Model'           => __DIR__.'/src/Model',
  'MVC\\Service'           => __DIR__.'/src/Service'
));


$loader->registerNamespaceFallback(__DIR__.'/src');
$loader->register();
?>