<?php
/**
 * Rewrite paths.
 * These are checked during Router::current() to determine if a page rule specified here should
 * override the process for determining controller load. 
 * 
 * Keys are rewrite rules, and values are destination.
 * The rules should be written with preg_match() in mind.
 * 
 * @sample arraY('/([-a-zA-Z0-9]{4,20})' => 'mycontroller/alphanum/$1'),
 */
\Registry::$config['rewrites'] = array();