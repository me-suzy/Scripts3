<?php

/** 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the LGPL License.
 *
 * Copyright(c) 2005 by Santiago Lizardo Oscares. All rights reserved.
 *
 * The latest version of Molins can be obtained from <http://www.phpize.com>.
 *
 * @author slizardo <santiago.lizardo@gmail.com>
 * @version $Id: Molins.php,v 1.20 2005/11/07 10:05:34 slizardo Exp $
 * @package default
 */

defineConstants();
defineGlobals();

$include_path = array(
	get_include_path(), 
	CONF_DIR_APP.DIRECTORY_SEPARATOR.'WEB-INF'.DIRECTORY_SEPARATOR.'classes'
);
set_include_path(implode(PATH_SEPARATOR, $include_path));

/**
 * We import the base classes for the operation of Molins.
 */
import('poop.lang.*');
import('poop.lang.reflect.ClassUtil');

/**
 * We check the requirements (extensiones, directives) for the correct work of Molins.
 */
checkRequirements();

import('poop.util.Locale');

import('poop.http.HttpRequest');
import('poop.http.HttpResponse');

$GLOBALS['request'] = new HttpRequest();
$GLOBALS['response'] = new HttpResponse();

@error_reporting(E_ALL);
@set_error_handler('PHP_errorHandler', E_ALL);

import('poop.http.Controller');

/**
 * And finally we process the request.
 */
Controller::processRequest();

function defineConstants() {
	$isWin = (strtoupper(substr(PHP_OS, 0, 3) == 'WIN'));
	if($isWin) {
		define('OS_WIN', true);
		define('NL', "\r\n");
	} else {
		define('OS_UNIX', true);
		define('NL', "\n");
	}
	
	define('CONTEXT_PATH', CONF_CONTEXT_PATH);
	define('CONF_DIR_APP', getcwd());	
}

function defineGlobals() {
	// TODO
}

function checkRequirements() {
	$reqs_file = implode(DIRECTORY_SEPARATOR, array(CONF_DIR_APP, 'WEB-INF', 'work', 'requirements.ser'));
	if(file_exists($reqs_file)) {
		return;
	}
	if(!in_array('mod_rewrite', apache_get_modules())) {
		throw new Exception('module mod_rewrite not found in Apache server');
	}

	$requiredExtensions = array('gettext', 'xsl', 'gd', 'pdo');
	foreach($requiredExtensions as $extension) {
		if(!extension_loaded($extension)) {
			throw new ExtensionNotFoundException($extension);
		}
	}
	
	file_put_contents($reqs_file, serialize(true));
}

/**
 * Includes the class passed as parameter to allow be used by the application.
 *
 * @param string $classpath
 */
function import($classPath) {
	if(in_array($classPath, get_included_files())) {
		return;
	} 
	
	$parts = explode('.', $classPath);

	if(array_pop($parts) == '*') {
		$path = implode(DIRECTORY_SEPARATOR, $parts);
		$molinsClasspath = CONF_DIR_MOLINS.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.$path;
		$webappClasspath = CONF_DIR_APP.DIRECTORY_SEPARATOR.'WEB-INF'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.$path;
		if(is_dir($molinsClasspath)) {
			$path = $molinsClasspath;
		} else
		if(is_dir($webappClasspath)) {
			$path = $webappClasspath;
		} else {
			throw new Exception(_('package no encontrado.'), $classPath);
		}
		$dir = opendir($path);
		while(($file = readdir($dir)) != false) {
			if(substr($file, -10) == '.class.php') {
				$filePath = explode('.', $path);
				array_push($filePath, $file);
				$filePath = implode(DIRECTORY_SEPARATOR, $filePath);
				@include_once $filePath;
			}
		}
		closedir($dir);
	} else {
		$path = str_replace('.', DIRECTORY_SEPARATOR, $classPath).'.class.php';
		
		@include_once $path;
	}
}

/**
 * This function is called by PHP when is trying to create an object
 * of an not found class.
 *
 * @param string $class
 */
function __autoload($class) {
	$classNotFoundException = new ClassNotFoundException($class);
	
	Molins_errorHandler($classNotFoundException);
}

/**
 * @param int $num_err
 * @param string $cadena_err
 * @param string $archivo_err,
 * @param int $linea_err
 */
function PHP_errorHandler($num_err, $cadena_err, $archivo_err, $linea_err) {
	$runtimeException = new RuntimeException($cadena_err, "$linea_err:$archivo_err");

	Molins_errorHandler($runtimeException);
}

/**
 * It catches y manage errors, displaying the error page.
 *
 * @param Exception $e
 */
function Molins_errorHandler(Exception $e) {
	import('poop.presentation.Template');

	$template_dir = implode(DIRECTORY_SEPARATOR, array(CONF_DIR_MOLINS, 'templates'));

	$template = new Template();
	$template->setTemplateDir($template_dir);
	$template->setTemplate('Molins_errorPage.tpl');
	$template->assign('Molins_title', 'Molins framework');
	$template->assign('Molins_version', '0.9.11');
	$template->assign('exception_type', get_class($e));
	$template->assign('exception_message', $e->getMessage());
	$template->assign('exception_description', $e->__toString());
	$template->assign('exception_backtrace', $e->getTraceAsString());

	print $template;
	exit(-1);
}

?>
