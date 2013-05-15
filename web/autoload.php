<?php
namespace MVC;

require_once __DIR__."/library/Autoloader.php";

$autoload = array();

$autoload[] = __DIR__.'/library';
$autoload[] = __DIR__.'/conf';
$autoload[] = __DIR__.'/src/controller';
$autoload[] = __DIR__.'/src/model';
$autoload[] = __DIR__.'/src/service';

new Library\Autoloader($autoload);


?>