<?php
$config = array
(
	// Expiration time in seconds.
	'lifetime' => SESSION_LIFETIME,
	'name' => SESSION_NAME,
	'cookie' => array 
	(
		'path' => SESSION_PATH,
		'domain' => SESSION_DOMAIN,
		'secure' => SESSION_SECURE,
		'httponly' => SESSION_HTTP_ONLY,
	)
);