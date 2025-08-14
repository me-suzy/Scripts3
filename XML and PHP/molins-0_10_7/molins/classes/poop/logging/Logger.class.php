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
 * @version $Id: Logger.class.php,v 1.13 2005/10/19 15:55:18 slizardo Exp $
 * @package poop.logging
 */

import('poop.xml.XMLException');
import('poop.logging.Level');
import('poop.lang.reflect.ClassUtil');
import('poop.cache.CacheUtil');
import('poop.logging.config.AppenderConfig');
import('poop.logging.config.PropertyConfig');
import('poop.logging.config.LayoutConfig');
import('poop.logging.config.LoggingConfig');

Logger::init();

final class Logger {

	private static $store;
	private static $appenders;

	/**
	 * Este metodo carga los appenders y layouts definidos en<br />
	 * el archivo de configuracion logging.xml.
	 */
	public static function init() {
		$xmlPath = implode(DIRECTORY_SEPARATOR, array(CONF_DIR_APP, 'WEB-INF', 'logging.xml'));

		/**
		 * If the file WEB-INF/logging.xml does not exist , we dont do nothing.
		 */
		if(!file_exists($xmlPath)) {
			return;
		}

		$xsdPath = implode(DIRECTORY_SEPARATOR, array(CONF_DIR_MOLINS, 'schemas', 'logging.xsd'));
		$serPath = implode(DIRECTORY_SEPARATOR, array(CONF_DIR_APP, 'WEB-INF', 'work', 'logging.ser'));
		
		if(filemtime($xmlPath) >= time()-CacheUtil::T_MINUTE && file_exists($serPath)) {
			$serialized = file_get_contents($serPath);
			self::$store = unserialize($serialized);
			self::initAppenders();
			return;
		}

		self::$store = new LoggingConfig();
		
		$dom = new DomDocument();
		if(!@$dom->load($xmlPath)) {
			throw new XMLException(basename($xmlPath));
		}
		if(!@$dom->schemaValidate($xsdPath)) {
			throw new XMLException(_('error validando esquema'), basename($xsdPath));
		}
		
		$xPath = new DomXpath($dom);		

		$appenders = $xPath->query('/logging/appender');
		for($i = 0; $i < $appenders->length; $i++) {
			$appender = $appenders->item($i);
	
			$appenderConfig = new AppenderConfig();
			$appenderConfig->setClass($appender->getAttribute('class'));
		
			$properties = $xPath->query('./property', $appender);
			for($p = 0; $p < $properties->length; $p++) {
				$property = $properties->item($p);

				$propertyConfig = new PropertyConfig();
				$propertyConfig->setName($property->getAttribute('name'));
				$propertyConfig->setValue($property->getAttribute('value'));

				$appenderConfig->addProperty($propertyConfig);
			}

			$layouts = $xPath->query('./layout', $appender);
			if($layouts->length == 1) {
				$layout = $layouts->item(0);

				$layoutConfig = new LayoutConfig();
				$layoutConfig->setClass($layout->getAttribute('class'));

				$appenderConfig->setLayout($layoutConfig);
			} else {
				$layoutConfig = new LayoutConfig();
				$layoutConfig->setClass('poop.logging.SimpleLayout');

				$appenderConfig->setLayout($layoutConfig);
			}

			self::$store->addAppender($appenderConfig);
		}

		$serialized = serialize(self::$store);
		file_put_contents($serPath, $serialized);

		self::initAppenders();
	}

	public static function initAppenders() {
		self::$appenders = array();
		foreach(self::$store->getAppenders() as $appenderConfig) {
			$appender = ClassUtil::importInstance($appenderConfig->getClass());
			$layout = ClassUtil::importInstance($appenderConfig->getLayout()->getClass());
			$appender->setLayout($layout);

			foreach($appenderConfig->getProperties() as $propertyConfig) {
				$appender->setProperty($propertyConfig->getName(), $propertyConfig->getValue());
			}
			
			$appender->init();
			
			array_push(self::$appenders, $appender);
		}
	}
	
	/**
	 * @param string $message
	 * @param Exception $exception
	 */
	public static function debug($message, $exception = null) {
		self::log(Level::DEBUG, $message, $exception);
	}

	/**
	 * @param string $message
	 * @param Exception $exception
	 */
	public static function info($message, $exception = null) {
		self::log(Level::INFO, $message, $exception);
	}

	/**
	 * @param string $message
	 * @param Exception $exception
	 */
	public static function warn($message, $exception = null) {
		self::log(Level::WARN, $message, $exception);
	}

	/**
	 * @param string $message
	 * @param Exception $exception
	 */
	public static function error($message, $exception = null) {
		self::log(Level::ERROR, $message, $exception);
	}

	/**
	 * @param string $message
	 * @param Exception $exception
	 */
	public static function fatal($message, $exception = null) {
		self::log(Level::FATAL, $message, $exception);
	}	

	/**
	 * Esta funcion recorre cada appender y le indica<br />
	 * que debe loguear el mensaje, nivel y exception<br />
	 * pasados como parametro.
	 *
	 * @param int $level
	 * @param string $message
	 * @param Exception $exception
	 */
	public static function log($level, $message, $exception) {
		foreach(self::$appenders as $appender) {
			$appender->log($level, $message, $exception);
		}
	}
}

?>
