<?php
namespace MVC;
require_once __DIR__.'/autoload.php';
require_once __DIR__.'/conf/conf.php';
$settings = new Library\Settings($conf);

$app = new Library\Application($settings);
$app->handleRequest();
?>
