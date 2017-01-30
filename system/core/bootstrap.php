<?php
/**
 * Bootstraps the framework.
 */

// Load framework assets.
require_once SYSTEM_CORE_DIRECTORY . DIRECTORY_SEPARATOR . 'Core.php';
require_once SYSTEM_CORE_DIRECTORY . DIRECTORY_SEPARATOR . 'Loader.php';
require_once SYSTEM_CORE_DIRECTORY . DIRECTORY_SEPARATOR . 'FrameworkError.php';
require_once SYSTEM_CORE_DIRECTORY . DIRECTORY_SEPARATOR . 'Config.php';
require_once SYSTEM_CORE_DIRECTORY . DIRECTORY_SEPARATOR . 'Router.php';

// Add any supplied packages to Loader section stack.
if (isset($packages))
{
  foreach ($packages as $package)
  {
    Loader::$sections[] = PACKAGES_DIRECTORY . DIRECTORY_SEPARATOR . $package;
  }
  unset ($packages);
}

// Set error reporting.
error_reporting(ERROR_REPORTING_LEVEL);

// Set default timezone.
if (defined(DEFAULT_TIMEZONE))
{
  date_default_timezone_set(DEFAULT_TIMEZONE);
}