<?php
// Setup Access Control
$acl = new Zend_Acl();

// Add roles, guest is non-logged in
$acl->addRole(new Zend_Acl_Role('guest'));
$acl->addRole(new Zend_Acl_Role('superadmin'));
$acl->addRole(new Zend_Acl_Role('admin'));
$acl->addRole(new Zend_Acl_Role('user'));

// Add resources
$acl->addResource(new Zend_Acl_Resource('authenticated'));
$acl->addResource(new Zend_Acl_Resource('admin'));
$acl->addResource(new Zend_Acl_Resource('admin:administrator'));

// Give admins permission to admin section
$acl->allow('superadmin');

$acl->allow('admin', 'admin');
$acl->deny('admin', 'admin:administrator');

// This is used to limit access to logged in users but not more finely grained control
$acl->allow('user', 'authenticated');

return $acl;