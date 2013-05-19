<?php
namespace AZ;
require_once __DIR__.'/autoload.php';
include __DIR__.'/conf/conf.php';
include __DIR__.'/conf/routes.php';

$app = new Framework\Application(__DIR__,$conf, $routes);
$app->handleRequest();
?>
