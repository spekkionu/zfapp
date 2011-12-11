<?php
$routes = array();

# General Routes #
#----------------------
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

return $routes;