<?php

$config = array();

//########################
// General Site Settings #
//########################

$config['site'] = array(
  // The name of the site. Used for emails and site titles
  'name' => 'My App',

  // The domain of the store.  Does not include the http:://
  'domain' => 'zfapp',

  // The directory the application is installed in. Use / if the application is installed in the document root
  // If mod rewrite is not available use /index.php/
  'base_url' => '/'
);

$config['ssl'] = array(
  // If ssl is enabled for this site
  'enable' => false,

  // The ssl domain of the store.  Does not include the http:://
  // Generally the same as the site domain unless using a shared ssl certificate
  'domain' => 'zfapp',

  // The port to use for secure pages.
  // Use port 443 for ssl and port 80 to fake ssl switching
  'port' => 443,

  // The base url for the ssl domain.
  // On shared servers this can often be /~account/
  'base_url' => '/'
);


//########################
// Locale Settings       #
//########################

$config['locale'] = array(
  // The timezone the server should use
  'timezone' => 'America/Los_Angeles',

  // The Default Locale
  'locale' => 'en_US'
);

//########################
// Session Handling      #
//########################

$config['session'] = array(
  // Classname for session handling, leave blank for default session handling
  'handler' => 'Session_SaveHandler_EncryptedFile',
  'options' => array(
    // Time for session to expire
    'remember_me_seconds' => 864000
  ),
  'handler_options' => array(
    // Random string for encryption, should be changed for each individual install
    'cryptKey' => 'hjuyghTYGFDBhgjrtedsGGFJHYUrfNTHYKRTYYFDbhgjtyju',
    // Encryption algorythm, Any mcrypt algorythm
    'cryptAlgorythm'=> MCRYPT_RIJNDAEL_256,
    // Name of the session table
    'name' => 'sessions',
    // Primary keys
    'primary' => array('session_id', 'save_path', 'name'),
    // column metadata
    'primaryAssignment' => array('sessionId', 'sessionSavePath', 'sessionName'),
    // Modified time column
    'modifiedColumn' => 'modified',
    // Data column
    'dataColumn' => 'session_data',
    // expire lifetime column
    'lifetimeColumn' => 'lifetime'
  )
);


//########################
// Caching settings      #
//########################

$config['cache'] = array(
  // Cache id prefix, can usually be left alone, needed if multiple applications share the same cache
  'prefix' => 'zfapp',

  // Caching for general data
  'general' => array(
    // If true caching will be used
    'enabled' => true,
    // The caching method
    'type' => 'File',
    // Set to true if using something other than a default Zend_Cache Backend
    'custom' => false,
    // The maximum lifetime of a cached entry, set to null for no expiration
    "lifetime" => null,
    // Any options for the cache backend go here.  Most will not need any
    'options' => array()
  ),
  // Caching for database schema and metadata
  'dbmetadata' => array(
    // If true caching will be used
    'enabled' => true,
    // The caching method
    'type' => 'File',
    // Set to true if using something other than a default Zend_Cache Backend
    'custom' => false,
    // The maximum lifetime of a cached entry, set to null for no expiration
    "lifetime" => null,
    // Any options for the cache backend go here.  Most will not need any
    'options' => array()
  ),
  // Caching for Full Page Content, This used Smarty Cache rather than Zend Cache
  'page' => array(
    // If true smarty caching will be used
    'enabled' => false,
    // The amount of time page is cached, set to 0 to cache forever
    'timeout' => 300,
    // If true file will be template will be checked for modifications, this is slightly slower
    'check_modified' => false
  )
);

//##############################
// Error reporting and logging #
//##############################

$config['debug'] = array(
  // Error reporting php setting
  // Set to 0 for production
  'error_reporting' => 0,
  // If true errors and exceptions will be shown.
  // Set to false for production
  'display_errors' => false,
  // If true errors and exceptions will be logged.
  'error_logging' => true,
  // If true send FirePHP headers to firebug
  'firebug' => false,
  // Show Debug Toolbar
  'debug_bar' => false
);

//########################
// Mail sending settings #
//########################

$config['mail'] = array(
  // Can be smtp or sendmail.  This sets the method of sending mails
  'type' => 'sendmail',
  // An email address to use as a force return path.
  'options' => array(
    'forcereturn' => null
  ),
  // SMTP mail settings, ignored if sendmail is used
  'smtp' => array(
    // SMTP Mail Server
    'server' => 'mail.example.com',
    'options' => array(
      // SSL type.  Comment out if no ssl is used. Can be tls or ssl
      'ssl' => 'tls',
      // The port to connect to the mail server with.  Generally 25 for tls and 465 for ssl.
      'port' => 25,
      // Auth type.  Can be plain, login or crammd5.  Comment lines if authentication is not needed.
      'auth' => 'login',
      // Username and password for the given mail account.
      'username' => 'username',
      'password' => 'password'
    )
  ),
  'test' => array(
    // If true all mail will be sent to test address rather than normal target
    'enabled' => false,
    // Array of test addresses
    'address' => array('test@example.com')
  ),
  // The address to send email from
  'from' => array(
    'email' => 'email@example.com',
    'name' => 'From Name'
  ),
  // Array of email addresses to send application notifications to
  'notify' => array('email@example.com')
);

//###############################
// Database Connection Settings #
//###############################

$config['database'] = array(
  'adapter' => 'Pdo_Mysql',
  'params' => array(
    'host' => 'localhost',
    'username' => 'username',
    'password' => 'password',
    'dbname' => 'dbname',
    'charset' => 'utf8',
    // Adapter specific parameters
    'options' => array()
  )
);
return $config;