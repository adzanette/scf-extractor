<?php
$routes = array();

$routes[''] = array(
  'pattern' => '',
  'controller' => 'SiteController',
  'method' => 'index'
);

$routes['404'] = array(
  'pattern' => '404',
  'controller' => 'SiteController',
  'method' => 'show404'
);

$routes['verb-list'] = array(
  'pattern' => 'verb-list/{corpus}/{page}',
  'controller' => 'SiteController',
  'method' => 'showVerbList'
);

$routes['example'] = array(
  'pattern' => 'example/path',
  'controller' => '\Controller\Index',
  'method' => 'run'
);

$routes['articles'] = array(
  'pattern' => 'articles/{year}',
  'controller' => '\Controller\Example\Param',
  'method' => 'run',
  'restrictions' => array('year' => '\d{4}')
);

$routes['blog'] = array(
  'pattern' => 'blog/{year}/{slug}',
  'controller' => '\Controller\Example\Param',
  'method' => 'run'
);
?>