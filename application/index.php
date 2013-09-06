<?php
/**
 * Front controller.
 * 
 * This should be placed in the web root of your application. It handles
 * all incoming requests for pages. It is highly recommended that all
 * other framework-related files be kept outside of the web root to 
 * prevent unintended access. 
 * 
 * To force all requests to this page, you should use .htaccess or other
 * available methods. 
 * 
 * For detailed information on how to use the framework:
 * @see https://github.com/negative11/negative11-chocolate/wiki/ 
 */

// Define the root directory containing all the framework files.
// By default, this points to the folder that contains the 'application' directory.
define ('ENVIRONMENT_ROOT', realpath(dirname(__DIR__)));

// Load the parameters file that contains more global configuration settings.
require_once ENVIRONMENT_ROOT . DIRECTORY_SEPARATOR . 'parameters.php';

// Load the framework bootstrap and go!
require_once SYSTEM_CORE_DIRECTORY . DIRECTORY_SEPARATOR . 'bootstrap' . FILE_EXTENSION;

Core::initialize();
Core::initializeErrorHandler();
Core::run();
Core::shutdown();