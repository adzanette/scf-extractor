<?php
$conf = array();

$conf['name'] = 'SCFViewer';
$conf['title'] = 'site.title';
$conf['version'] = '1.0';

$conf['http-host'] = 'http://zanette.extractor/';
$conf['url-media'] = $conf['http-host'].'resources/web/';

$conf['template'] = array(
  'title' => $conf['title'],
  'locale' => 'pt',
  'version' => $conf['version'],
  'url-media' => $conf['url-media'],
  'url-media-css' => $conf['url-media'].'css/',
  'url-media-js' => $conf['url-media'].'js/',
  'url-media-img' => $conf['url-media'].'img/',
  'page-size' => 50,
  'page-window' => 12
);

$conf['session'] = array(
  'name' => 'session'
);

$conf['router'] = array(
  'domain' => $conf['http-host'],
  'ignore' => ''
);

$conf['translator'] = array(
    'domain' => 'messages',
    'folder' => 'resources/translations/'
);

$conf['cache'] = array(
  'type' => 'array',
  'default-timeout' => 100,
  'enabled' => true
);

$conf['database'] = array(
    'dsn' => 'mysql:host=127.0.0.1;port=3306;',
    'dbname' => 'scf-test',
    'username' => 'zanette',
    'password' => 'zanette',
    'params' => array()
);

$conf['databases'] = array(
    'scf-amazonia' => 'database.amazonia',
    'scf-cardiologia' => 'database.cardiologia',
    'scf-diario-gaucho' => 'database.diario',
    'scf-bosque' => 'database.bosque',
    'scf-bosquefull' => 'database.bosque.completo',
    'scf-lacio' => 'database.verbnet',
    'scf-test' => 'database.teste'
);

$conf['roles'] = array(
   'agente' => 'role.agente'
  ,'paciente' => 'role.paciente'
  ,'tema' => 'role.tema'
  ,'instrumento' => 'role.instrumento'
  ,'experienciador' => 'role.experienciador'
  ,'experienciado' => 'role.experienciado'
  ,'beneficiario' => 'role.beneficiario'
);
?>