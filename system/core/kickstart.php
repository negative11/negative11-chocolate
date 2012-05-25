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

// Go!
Core::initialize();
Core::run();
Core::shutdown();