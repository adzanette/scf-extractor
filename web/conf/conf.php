<?php
$conf = array();

$conf['name'] = "SCF-viewer";
$conf['title'] = "site.title";
$conf['version'] = "1.0";

$conf['http-host'] = "http://localhost/scf-viewer/";
$conf['url-media'] = $conf['http-host'].'resources/web/';

$conf['template'] = array(
  'title' => $conf['title'],
  'locale' => 'en',
  'version' => $conf['version'],
  'url-media' => $conf['url-media'],
  'url-media-css' => $conf['url-media'].'css/',
  'url-media-js' => $conf['url-media'].'js/',
  'url-media-img' => $conf['url-media'].'img/',
  "page-size" => 50,
  'page-window' => 12
);

$conf['session'] = array(
  "name" => "session"
);

$conf['router'] = array(
    'ignore' => "/scf-viewer/index.php/"
);

$conf['translator'] = array(
    'domain' => "messages",
    'folder' => 'resources/translations/'
);

$conf['database'] = array(
    'dsn' => "mysql:host=127.0.0.1;port=3306;",
    'dbname' => 'scf-teste',
    'username' => 'root',
    'password' => 'zanette',
    'params' => array()
);

$conf['databases'] = array(
    'scf-cardiologia' => 'database.cardiologia',
    'scf-diario-gaucho' => 'database.diario',
    'scf-lacio' => 'database.lacio',
    'scf-teste' => 'database.teste'
);
?>