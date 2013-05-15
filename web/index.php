<?php
namespace MVC;
require_once __DIR__.'/autoload.php';
include __DIR__.'/conf/conf.php';
include __DIR__.'/conf/routes.php';

$settings = new Library\Settings($conf);

$app = new Library\Application($settings, $routes);
$app->handleRequest();
?>
