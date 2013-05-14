<?php
namespace MVC;
require_once __DIR__.'/autoload.php';

$settings = new Modules\Settings(__DIR__."/conf/conf.json");

$config = array(
  'dns' => "mysql:host=127.0.0.1;port=3306;dbname=scf-cardiologia",
  'username' => 'root',
  'password' => 'zanette',
  'params' => array()
);

$db = new Modules\Database($config);

if(empty(Modules\ORM::$db)){
  Modules\ORM::$db = $db;
}

$verbs = Model\Verb::fetch();
foreach($verbs as $verb){
  echo $verb->verb.'<br>';
}
$app = new Modules\Application($settings);
$app->handleRequest();
?>
