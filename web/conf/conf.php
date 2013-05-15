<?php
$conf = array();

$conf['name'] = "SCF-viewer";
$conf['title'] = "SCF-Viewer";
$conf['default-locale'] = "en";
$conf['version'] = "1.0";

$conf['http-host'] = "http://localhost/scf-viewer/";
$conf['url-media'] = $conf['http-host'].'resources/web/';
$conf['url-media-css'] = $conf['url-media'].'css/';
$conf['url-media-js'] = $conf['url-media'].'js/';
$conf['url-media-img'] = $conf['url-media'].'img/';

$conf['session'] = array(
  "name" => "session"
);

$conf['router'] = array(
    'ignore' => "/scf-viewer/index.php/"
);

$conf['tranlator'] = array(
    'domain' => "messages",
    'folder' => 'resources/translations/'
);

$conf['database'] = array(
    'dns' => "mysql:host=192.168.67.36;port=3306;dbname=testes",
    "username" => "admin",
    "password" => "surfsurfsurf",
    "params" => array()
);

?>