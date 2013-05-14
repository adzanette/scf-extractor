<?php
namespace MVC;

require_once __DIR__."/modules/Autoloader.php";

$autoload = array();

$autoload[] = __DIR__.'/modules';
$autoload[] = __DIR__.'/controller';
$autoload[] = __DIR__.'/conf';
$autoload[] = __DIR__.'/model';
//$autoload[] = __DIR__.'/modules';

new Modules\Autoloader($autoload);


?>