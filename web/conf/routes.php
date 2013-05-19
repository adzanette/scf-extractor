<?php
$routes = array();

$routes['index'] = array(
  'pattern' => '',
  'controller' => 'SiteController',
  'method' => 'index'
);

$routes['select-corpus'] = array(
  'pattern' => 'corpus/select',
  'controller' => 'SiteController',
  'method' => 'selectCorpus'
);

$routes['404'] = array(
  'pattern' => '404',
  'controller' => 'SiteController',
  'method' => 'show404'
);

$routes['verb-list'] = array(
  'pattern' => 'verbs/list/{corpus}/{page}',
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
  'pattern' => 'frames/list/{corpus}/{verbId}/{verbPage}/{page}',
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
  'pattern' => 'examples/list/{corpus}/{verbId}/{verbPage}/{frameId}/{framePage}/{page}',
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

$routes['delete-example'] = array(
  'pattern' => 'ajax/example/delete/{corpus}',
  'controller' => 'SiteController',
  'method' => 'deleteExample'
);

$routes['delete-argument'] = array(
  'pattern' => 'ajax/argument/delete/{corpus}',
  'controller' => 'SiteController',
  'method' => 'deleteArgument'
);

$routes['save-argument'] = array(
  'pattern' => 'ajax/argument/save/{corpus}',
  'controller' => 'SiteController',
  'method' => 'saveArgument'
);

$routes['semantic-frames-list'] = array(
  'pattern' => 'semantic-frames/list/{corpus}/{page}',
  'controller' => 'SiteController',
  'method' => 'showSemanticFramesList',
  'defaults' => array(
    'page' => 1
  ),
  'restrictions' => array(
    'page' => '\d+'
  )
);

$routes['semantic-frames-verbs'] = array(
  'pattern' => 'semantic-frames/list/verbs/{corpus}/{framePage}/{frame}',
  'controller' => 'SiteController',
  'method' => 'showVerbSemanticFramesList',
  'defaults' => array(
    'framePage' => 1
  ),
  'restrictions' => array(
    'framePage' => '\d+'
  )
);

$routes['semantic-frame-examples'] = array(
  'pattern' => 'semantic-frames/examples/{corpus}/{framePage}/{frame}/{verbId}',
  'controller' => 'SiteController',
  'method' => 'showSemanticFrameExamples',
  'defaults' => array(
    'framePage' => 1
  ),
  'restrictions' => array(
    'framePage' => '\d+'
  )
);

$routes['semantic-frames'] = array(
  'pattern' => 'semantic-frames/list/by-verbs/{corpus}/{verbId}/{verbPage}/{page}',
  'controller' => 'SiteController',
  'method' => 'showSemanticFramesListByVerb',
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