<?php

// Default file extension
define('FILE_EXTENSION', '.php');

// Load the framework and go!
require_once ENVIRONMENT_ROOT . '/system/core/Core.php';
require_once ENVIRONMENT_ROOT . '/system/core/Loader.php';
require_once ENVIRONMENT_ROOT . '/system/core/Error.php';
require_once ENVIRONMENT_ROOT . '/system/core/Config.php';
require_once ENVIRONMENT_ROOT . '/system/core/Router.php';

// Add packages to loader section stack
$packages = array(
  'mysql'
);

foreach ($packages as $package)
{
	Loader::$sections[] = "packages/{$package}";
}
unset ($packages);

// Start buffer
ob_start();

// Get system configuration parameters
Loader::get('config' . DIRECTORY_SEPARATOR . 'core');

// Register autload function
spl_autoload_register('Core::autoload');

// Provide framework with a session.
new component\Session;