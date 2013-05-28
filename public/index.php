<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// PHP 5.4's built-in webserver uses this
if (php_sapi_name() == 'cli-server') {
	$url = $_SERVER['REQUEST_URI'];

	if($url && $url !== "/") {
		// Querystring args need to be explicitly parsed
		if (strpos($url, '?') !== false)
			list ($url) = explode('?', $url, 1);

		// Pass back to the webserver for files that exist
		if (file_exists(__DIR__ . "/{$url}"))
			return false;
	}
	
	unset($url);
}

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
