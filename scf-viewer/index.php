<?php
namespace AZ;
require_once __DIR__.'/autoload.php';
include __DIR__.'/conf/conf.php';
include __DIR__.'/conf/routes.php';
use AZ\Framework\Kernel\Application;

$app = new Application(__DIR__,$conf, $routes);
$app->handleRequest();
?>
