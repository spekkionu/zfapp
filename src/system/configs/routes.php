<?php
$routes = array();

# General Routes #
#----------------------
$routes['home'] = new Zend_Controller_Router_Route_Static(
  '/',
  array(
    'module' => 'default',
    'controller' => 'index',
    'action' => 'index'
  )
);
$routes['error'] = new Zend_Controller_Router_Route_Static(
  'error',
  array(
    'module' => 'default',
    'controller' => 'error',
    'action' => 'error'
  )
);
$routes['not_found'] = new Zend_Controller_Router_Route_Static(
  'not-found',
  array(
    'module' => 'default',
    'controller' => 'error',
    'action' => 'not-found'
  )
);
$routes['access_denied'] = new Zend_Controller_Router_Route_Static(
  'access-denied',
  array(
    'module' => 'default',
    'controller' => 'error',
    'action' => 'access-denied'
  )
);







# Admin Routes #
#----------------------
$routes['admin_home'] = new Zend_Controller_Router_Route_Static(
  'admin',
  array(
    'module' => 'admin',
    'controller' => 'index',
    'action' => 'index'
  )
);
$routes['admin_login'] = new Zend_Controller_Router_Route_Static(
  'admin/login',
  array(
    'module' => 'admin',
    'controller' => 'access',
    'action' => 'index'
  )
);
$routes['admin_logout'] = new Zend_Controller_Router_Route_Static(
  'admin/logout',
  array(
    'module' => 'admin',
    'controller' => 'access',
    'action' => 'logout'
  )
);
$routes['admin_forgot_password'] = new Zend_Controller_Router_Route_Static(
  'admin/reset-password',
  array(
    'module' => 'admin',
    'controller' => 'access',
    'action' => 'reset-password'
  )
);
$routes['admin_save_password'] = new Zend_Controller_Router_Route(
  'admin/reset-password/:token',
  array(
    'module' => 'admin',
    'controller' => 'access',
    'action' => 'save-password'
  ),
  array(
    'token' => '[0-9a-f]{32}'
  )
);

// Account and profile routes
// --------------------------
$routes['admin_account'] = new Zend_Controller_Router_Route_Static(
  'admin/account',
  array(
    'module' => 'admin',
    'controller' => 'account',
    'action' => 'index'
  )
);
$routes['admin_account_profile'] = new Zend_Controller_Router_Route_Static(
  'admin/account/profile',
  array(
    'module' => 'admin',
    'controller' => 'account',
    'action' => 'edit'
  )
);
$routes['admin_account_password'] = new Zend_Controller_Router_Route_Static(
  'admin/account/password',
  array(
    'module' => 'admin',
    'controller' => 'account',
    'action' => 'password'
  )
);

// Administrator management
// --------------------------
$routes['admin_administrator'] = new Zend_Controller_Router_Route(
  'admin/administrator/:page/:sort/:dir',
  array(
    'module' => 'admin',
    'controller' => 'administrator',
    'action' => 'index',
    'page' => 1,
    'sort' => 'id',
    'dir' => 'asc'
  ),
  array(
    'page' => '\d+',
    'sort' => 'id|username|email|name|active|last_login',
    'dir' => 'asc|desc'
  )
);
$routes['admin_administrator_add'] = new Zend_Controller_Router_Route_Static(
  'admin/administrator/add',
  array(
    'module' => 'admin',
    'controller' => 'administrator',
    'action' => 'add'
  )
);
$routes['admin_administrator_edit'] = new Zend_Controller_Router_Route(
  'admin/administrator/edit/:id',
  array(
    'module' => 'admin',
    'controller' => 'administrator',
    'action' => 'edit'
  ),
  array(
    'id' => '\d+'
  )
);
$routes['admin_administrator_delete'] = new Zend_Controller_Router_Route(
  'admin/administrator/delete/:id',
  array(
    'module' => 'admin',
    'controller' => 'administrator',
    'action' => 'delete'
  ),
  array(
    'id' => '\d+'
  )
);

// Content management
// --------------------------
$routes['admin_content'] = new Zend_Controller_Router_Route(
  'admin/content/:page/:sort/:dir',
  array(
    'module' => 'admin',
    'controller' => 'content',
    'action' => 'index',
    'page' => 1,
    'sort' => 'url',
    'dir' => 'asc'
  ),
  array(
    'page' => '\d+',
    'sort' => 'id|url|title|active|date_created|last_updated',
    'dir' => 'asc|desc'
  )
);
$routes['admin_content_add'] = new Zend_Controller_Router_Route_Static(
  'admin/content/add',
  array(
    'module' => 'admin',
    'controller' => 'content',
    'action' => 'add'
  )
);
$routes['admin_content_edit'] = new Zend_Controller_Router_Route(
  'admin/content/edit/:id',
  array(
    'module' => 'admin',
    'controller' => 'content',
    'action' => 'edit'
  ),
  array(
    'id' => '\d+'
  )
);
$routes['admin_content_delete'] = new Zend_Controller_Router_Route(
  'admin/content/delete/:id',
  array(
    'module' => 'admin',
    'controller' => 'content',
    'action' => 'delete'
  ),
  array(
    'id' => '\d+'
  )
);


return $routes;