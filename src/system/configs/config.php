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
  'base_url' => '/',

  'webroot' => (isset($_SERVER['DOCUMENT_ROOT']) && $_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : dirname(__DIR__).DIRECTORY_SEPARATOR.'public_html'
);

$config['ssl'] = array(
  // If ssl is enabled for this site
  'enable' => false,

  // The port to use for secure pages.
  // Use port 443 for ssl and port 80 to fake ssl switching
  'port' => 443,
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

//#########################
// CDN Settings           #
//#########################

$config['cdn'] = array(

  // Url of the CDN used for asset files.
  // Leave blank to not use a cdn
  'asset' => null,

);

//########################
// Session Handling      #
//########################

$config['session'] = array(
  // Classname for session handling, leave blank for default session handling
  'handler' => '',
  'options' => array(
    // Time for session to expire
    'remember_me_seconds' => 900
  ),
  'handler_options' => array(

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
    'enabled' => false,
    // The caching method
    'type' => 'File',
    // Set to true if using something other than a default Zend_Cache Backend
    'custom' => false,
    // Fallback to File cache
    'fallback' => true,
    // The maximum lifetime of a cached entry, set to null for no expiration
    "lifetime" => null,
    // Any options for the cache backend go here.  Most will not need any
    'options' => array()
  ),
  // Caching for settings data
  'settings' => array(
    // If true caching will be used
    'enabled' => false,
    // The caching method
    'type' => 'File',
    // Set to true if using something other than a default Zend_Cache Backend
    'custom' => false,
    // Fallback to File cache
    'fallback' => false,
    // The maximum lifetime of a cached entry, set to null for no expiration
    "lifetime" => 3600,
    // Any options for the cache backend go here.  Most will not need any
    'options' => array()
  ),
  // Caching for database schema and metadata
  'dbmetadata' => array(
    // If true caching will be used
    'enabled' => false,
    // The caching method
    'type' => 'File',
    // Set to true if using something other than a default Zend_Cache Backend
    'custom' => false,
    // Fallback to File cache
    'fallback' => false,
    // The maximum lifetime of a cached entry, set to null for no expiration
    "lifetime" => 3600,
    // Any options for the cache backend go here.  Most will not need any
    'options' => array()
  ),
  // Caching for Zend_Locale
  'locale' => array(
    // If true caching will be used
    'enabled' => false,
    // The caching method
    'type' => 'File',
    // Set to true if using something other than a default Zend_Cache Backend
    'custom' => false,
    // Fallback to File cache
    'fallback' => false,
    // The maximum lifetime of a cached entry, set to null for no expiration
    "lifetime" => null,
    // Any options for the cache backend go here.  Most will not need any
    'options' => array()
  ),
  // Caching for Zend_Translate
  'translate' => array(
    // If true caching will be used
    'enabled' => false,
    // The caching method
    'type' => 'File',
    // Set to true if using something other than a default Zend_Cache Backend
    'custom' => false,
    // Fallback to File cache
    'fallback' => false,
    // The maximum lifetime of a cached entry, set to null for no expiration
    "lifetime" => null,
    // Any options for the cache backend go here.  Most will not need any
    'options' => array()
  ),
  // Caching for HTMLPurifier
  'htmlpurifier' => array(
    // If true caching will be used
    'enabled' => false
  ),
  // Caching for plugin loader
  'plugin' => array(
    // If true caching will be used
    'enabled' => false
  ),
  // Caching for full page caching
  'page' => array(
    // If true caching will be used
    'enabled' => false,
    //  Can avoid cache corruption under bad circumstances but it doesn't help on multithread webservers or on NFS filesystems
    'file_locking' => true,
    // if enabled, a control key is embedded in the cache file and this key is compared with the one calculated after the reading.
    'read_control' => true,
    // Type of read control (only if read control is enabled). Available values are :
    // 'md5' (best but slowest),
    // 'crc32' (lightly less safe but faster, better choice),
    // 'adler32' (new choice, faster than crc32),
    // 'strlen' for a length only test (fastest).
    'read_control_type' => 'crc32',
    // umask for cached files
    'cache_file_umask' => 0600,
    // Umask for directories created within public_dir.
    'cache_directory_umask' => 0700,
    // Default file extension for static files created.
    'file_extension' => '.html',
    // Home page cache name
    'index_filename' => 'index',
  ),
  // Caching for the tags of full page caching
  'pagetag' => array(
    // If true caching will be used
    'enabled' => true,
    // The caching method
    'type' => 'File',
    // Set to true if using something other than a default Zend_Cache Backend
    'custom' => false,
    // Fallback to File cache
    'fallback' => true,
    // The maximum lifetime of a cached entry, set to null for no expiration
    "lifetime" => 300,
    // Any options for the cache backend go here.  Most will not need any
    'options' => array()
  ),
);

//##########################
// Encryption and hashing  #
//##########################

$config['security'] = array(
  'crypt' => array(
    // Salt used for encrypting settings, user passwords, and other data
    // Should be changed for each install but changing will invalidate existing data.
    'key' => 'wSrloQj66OLlQJbEjKve/.m9jhiXo340',
    // Encryption Algorithm to use.
    // Can be any mcrypt encryption algorithm returned by mcrypt_list_algorithms()
    'algorithm' => 'rijndael-256',
    // mcrypt mode
    'mode' => 'ecb'
  )
);

//##############################
// Error reporting and logging #
//##############################

$config['debug'] = array(
  // Error reporting php setting
  // Leave at -1 to log all errors.
	// Better to turn off display_errors than change this which also changes logging.
  'error_reporting' => E_ALL,
  // If true errors and exceptions will be shown.
  // Set to false for production
  'display_errors' => false,
  // If true errors and exceptions will be logged.
  'error_logging' => true,
  // If true send FirePHP headers to firebug
  'firebug' => false,
  // Show Debug Toolbar
  'debug_bar' => false,
  // Profile with xhprof
  'xhprof' => array(
    // If true it will be enabled
    'enabled' => false,
    // Identifier for the run
    'key' => 'xhprof_zfapp',
    // Directory with xhprof php libraries
    'root' => '/usr/share/php',
    // Url for profile viewing
    'base_url' => $config['site']['domain'].'/xhprof',
    // Options passed to xhprof_enable XHPROF_FLAGS_MEMORY | XHPROF_FLAGS_CPU | XHPROF_FLAGS_NO_BUILTINS
    'options' => 0,
    // IP addresses allowed to view the url
    'allowed' => array('127.0.0.1'),
    // the trigger GET param
    'trigger' => 'xhprof'
  )
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
   // Send copies of all emails to these addresses
  'bcc' => array(
    // If true send copy of all mails
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

if(file_exists(__DIR__.'/config.local.php')){
  // Load local config overrides
  require(__DIR__.'/config.local.php');
}

return $config;
