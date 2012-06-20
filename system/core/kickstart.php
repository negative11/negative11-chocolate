<?php
/**
 * Kick-starts the framework.
 */
require_once 'core.php';

// Add packages to loader section stack
foreach ($packages as $package)
{
	Loader::$sections[] = "packages/{$package}";
}
unset ($packages);

/**
 * The $_SERVER variable the framework should use to determine translated path.
 * This may need to be changed based on your server/Apache/mod_rewrite settings. 
 * Options: PHP_SELF, PATH_INFO, REQUEST_URI.
 * 
 * @note that this may affect your ability to run the framework via command line.
 */
const CORE_SERVER_VAR = 'PHP_SELF';

// Go!
Core::initialize();
Core::run();
Core::shutdown();