<?php
namespace AZ;
require_once __DIR__."/az/Kernel/Autoloader.php";
use AZ\Framework\Kernel\Autoloader;

$loader = new Autoloader();
$loader->registerNamespaces(array(
  'AZ\\Framework'           => __DIR__.'/az',
  'SCFViewer\\Controller'           => __DIR__.'/src/SCFViewer',
));

$loader->registerNamespaceFallback(__DIR__.'/src');
$loader->register();
?>