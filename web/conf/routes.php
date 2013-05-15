<?php
$routes = array();

$routes['index'] = array(
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

$routes['frame-list'] = array(
  'pattern' => 'frame-list/{corpus}/{verbId}/{verbPage}/{page}',
  'controller' => 'SiteController',
  'method' => 'showFrameList'
);

$routes['example-list'] = array(
  'pattern' => 'example-list/{corpus}/{verbId}/{verbPage}/{frameId}/{$framePage}/{page}',
  'controller' => 'SiteController',
  'method' => 'showExampleList'
);

$routes['semantic-frames-list'] = array(
  'pattern' => 'semantic-frames-list/{corpus}/{page}',
  'controller' => 'SiteController',
  'method' => 'showSemanticFramesList'
);

$routes['sematic-frames'] = array(
  'pattern' => 'frame-list/{corpus}/{verbId}/{verbPage}/{page}',
  'controller' => 'SiteController',
  'method' => 'showFrameList'
);


?>