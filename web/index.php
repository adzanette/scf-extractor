<?php
namespace MVC;
require_once __DIR__.'/autoload.php';
include __DIR__.'/conf/conf.php';
include __DIR__.'/conf/routes.php';

$settings = new Library\ParameterBag($conf);
$teste = 'teste';
$app = new Library\Application($conf, $routes);
$app->handleRequest();
?>
