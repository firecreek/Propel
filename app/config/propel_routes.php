<?php

  // Installer
  if(!file_exists(CONFIGS.'settings.php'))
  {
    Router::connect('/', array('plugin' => 'install' ,'controller' => 'install'));
    return;
  }
  
  
  //Connect named
  Router::connectNamed(array('category','view','edit'));
  
  //Extensions
  Router::parseExtensions('rss','json','ajax','xml','js'); 

  Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
  Router::connect('/users/login', array('controller' => 'users', 'action' => 'login'));
  Router::connect('/users/logout', array('controller' => 'users', 'action' => 'logout'));
  Router::connect('/users/register', array('controller' => 'users', 'action' => 'register'));
  Router::connect('/users/invitation/*', array('controller' => 'users', 'action' => 'invitation'));
  Router::connect('/users/reset/*', array('controller' => 'users', 'action' => 'reset'));
  
  
  //Admin
  Router::connect('/admin/:controller/:action/*',
    array('controller'=>'dashboard', 'action'=>'index', 'prefix'=>'admin', 'admin'=>true)
  );
  Router::connect('/admin',array('controller'=>'dashboard', 'action'=>'index', 'prefix'=>'admin', 'admin'=>true));
  
  //Comments
  Router::connect('/:accountSlug/:projectId/:associatedController/comments/:action/*',
    array('controller'=>'comments', 'action'=>'index'),
    array('accountSlug'=>'[a-z0-9\-]+','projectId'=>'[0-9]+','associatedController'=>'[a-z0-9\_\-]+')
  );
  
  //Categories
  Router::connect('/:accountSlug/:projectId/categories/:action/*',
    array('controller'=>'categories', 'action'=>'index'),
    array('accountSlug'=>'[a-z0-9\-]+','projectId'=>'[0-9]+')
  );
  Router::connect('/:accountSlug/categories/:action/*',
    array('controller'=>'categories', 'action'=>'index'),
    array('accountSlug'=>'[a-z0-9\-]+')
  );
  
  
  //Projects
  Router::connect('/:accountSlug/:projectId',
    array('controller'=>'projects', 'action'=>'start', 'prefix'=>'project'),
    array('accountSlug'=>'[a-z0-9\-]+','projectId'=>'[0-9]+')
  );
  Router::connect('/:accountSlug/:projectId/:controller/:action/*',
    array('controller'=>'projects', 'action'=>'index', 'prefix'=>'project'),
    array('accountSlug'=>'[a-z0-9\-]+','projectId'=>'[0-9]+')
  );
  
  
  //Accounts
  Router::connect('/:accountSlug',
    array('controller'=>'users', 'action'=>'login'),
    array('accountSlug'=>'[a-z0-9\-]+')
  );
  Router::connect('/:accountSlug/index',
    array('controller'=>'accounts', 'action'=>'index', 'prefix'=>'account'),
    array('accountSlug'=>'[a-z0-9\-]+')
  );
  Router::connect('/:accountSlug/:controller/:action/*',
    array('controller'=>'accounts', 'action'=>'index', 'prefix'=>'account'),
    array('accountSlug'=>'[a-z0-9\-]+')
  );
  
  

?>
