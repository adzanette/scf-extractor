<?php
//global $routes;
$routes = array();

$routes[''] = array(
  'pattern' => '',
  'controller' => 'SiteController',
  'method' => 'test'
);

$routes['404'] = array(
  'pattern' => '404',
  'controller' => '\Controller\Page404',
  'method' => 'run'
);

$routes['school'] = array(
  'pattern' => 'school',
  'controller' => '\Controller\School',
  'method' => 'run'
);

$routes['example'] = array(
  'pattern' => 'example/path',
  'controller' => '\Controller\Index',
  'method' => 'run'
);

$routes['articles'] = array(
  //'pattern' => 'articles/(?P<year>\d{4})',
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