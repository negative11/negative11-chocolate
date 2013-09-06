<?php
/**
 * Contains various global configuration settings used throughout the framework.
 */

/**
 * Error reporting level.
 * The default setting is the most appropriate for catching bugs during development.
 */
define('ERROR_REPORTING_LEVEL', (E_ALL | E_STRICT));

// Default file extension.
define ('FILE_EXTENSION', '.php');

/**
 * This should be set to TRUE when deploying to production server. 
 * Doing so will disable some of the example code and debugger display. 
 * You can also test for this condition in your own code to disable 
 * certain features in production.
 */
define ('IN_PRODUCTION', FALSE);

/**
 * Define packages here. 
 * Packages extend the framework beyond base system functionality, and
 * it is recommended that you provide at least one package for your 
 * application.
 * 
 * Packages are stored in ENVIRONMENT_ROOT/packages.
 * The following array contains package folders relative to this path.
 * 
 * When model/view/controller conflicts occur, the package latest in the
 * array wins out. You can control explicit overloading of MVC 
 * components by organizing your packages accordingly.
 */
$packages = array 
(
	'example',
  'mysql',
  'curl',
);

// Set your time zone. 
// You may set this in your php.ini and remove this line.
define('DEFAULT_TIMEZONE', 'UTC');

/**
 * Where the various framework components can be found.
 * By default, everything is relative to the parent directory containing the 'application' directory.
 * These only need to be changed if the directories have been moved from their default locations.
 */
define('APPLICATION_DIRECTORY', ENVIRONMENT_ROOT . DIRECTORY_SEPARATOR . 'application');
define('PACKAGES_DIRECTORY', ENVIRONMENT_ROOT . DIRECTORY_SEPARATOR . 'packages');
define('SYSTEM_DIRECTORY', ENVIRONMENT_ROOT . DIRECTORY_SEPARATOR . 'system');
define('SYSTEM_CORE_DIRECTORY', SYSTEM_DIRECTORY . DIRECTORY_SEPARATOR . 'core');

/**
 * The $_SERVER variable the framework should use to determine translated path.
 * This may need to be changed based on your server/Apache/mod_rewrite settings. 
 * Options: PHP_SELF, PATH_INFO, REQUEST_URI. 
 * @note that this may affect your ability to run the framework via command line.
 */
define('CORE_SERVER_VAR', 'PHP_SELF');