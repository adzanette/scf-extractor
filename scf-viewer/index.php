<?php
namespace MVC;
require_once __DIR__.'/autoload.php';
include __DIR__.'/conf/conf.php';
include __DIR__.'/conf/routes.php';

use MVC\Library\ParameterBag;

$settings = new ParameterBag($conf);

$app = new Library\Application($conf, $routes);
$app->handleRequest();
?>
