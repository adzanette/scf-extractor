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
  'method' => 'showVerbList',
  'defaults' => array(
    'page' => 1
  ),
  'restrictions' => array(
    'page' => '\d+'
  )
);

$routes['frame-list'] = array(
  'pattern' => 'frame-list/{corpus}/{verbId}/{verbPage}/{page}',
  'controller' => 'SiteController',
  'method' => 'showFrameList',
  'defaults' => array(
    'page' => 1
  ),
  'restrictions' => array(
    'page' => '\d+',
    'verbPage' => '\d+',
    'verbId' => '\d+'
  )
);

$routes['example-list'] = array(
  'pattern' => 'example-list/{corpus}/{verbId}/{verbPage}/{frameId}/{$framePage}/{page}',
  'controller' => 'SiteController',
  'method' => 'showExampleList',
  'defaults' => array(
    'page' => 1
  ),
  'restrictions' => array(
    'page' => '\d+',
    'verbPage' => '\d+',
    'verbId' => '\d+',
    'framePage' => '\d+',
    'frameId' => '\d+'
  )
);

$routes['semantic-frames-list'] = array(
  'pattern' => 'semantic-frames-list/{corpus}/{page}',
  'controller' => 'SiteController',
  'method' => 'showSemanticFramesList',
  'defaults' => array(
    'page' => 1
  ),
  'restrictions' => array(
    'page' => '\d+'
  )
);

$routes['sematic-frames'] = array(
  'pattern' => 'frame-list/{corpus}/{verbId}/{verbPage}/{page}',
  'controller' => 'SiteController',
  'method' => 'showFrameList',
  'defaults' => array(
    'page' => 1
  ),
  'restrictions' => array(
    'page' => '\d+',
    'verbPage' => '\d+',
    'verbId' => '\d+'
  )
);


?>