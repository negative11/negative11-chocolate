<?php

// Load the parameters file that contains more global configuration settings.
require_once ENVIRONMENT_ROOT . DIRECTORY_SEPARATOR . 'parameters.php';

// Load the framework bootstrap and go!
require_once SYSTEM_CORE_DIRECTORY . DIRECTORY_SEPARATOR . 'bootstrap' . FILE_EXTENSION;

Core::initialize();