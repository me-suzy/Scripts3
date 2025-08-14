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
 * @version $Id: ResourceBundle.class.php,v 1.9 2005/10/16 20:01:04 slizardo Exp $
 * @package poop.util
 */

import('poop.util.Locale');
import('poop.io.FileNotFoundException');
 
class ResourceBundle {

	private $parent;
	private $properties;
	private $locale;
	
	/**
	 * @param array $properties
	 */
	private function __construct($properties) {
		$this->properties = $properties;
	}
	
	/**
	 * Gets a resource bundle using the specified base name and locale.
	 *
	 * @param string $baseName
	 * @param Locale $locale
	 * @return ResourceBundle
	 */	
	public static function getBundle($baseName, Locale $locale = null) {
	
		$tries = array();
	
		$buffer = new StringBuffer();
		$buffer->append($baseName);
		array_push($tries, $buffer->__toString());
		
		$buffer->append('_')->append($locale->getLanguage());
		array_push($tries, $buffer->__toString());
		
		if(!is_null($locale->getCountry())) {
			$buffer->append('_')->append($locale->getCountry());
			array_push($tries, $buffer->__toString());
		}

		foreach(array_reverse($tries) as $try) {
			$fileName = implode(DIRECTORY_SEPARATOR, array(CONF_DIR_APP, 'WEB-INF', 'classes', $try.'.properties'));
			if(file_exists($fileName)) {
				$properties = parse_ini_file($fileName);
				$resourceBundle = new ResourceBundle($properties);
				$resourceBundle->setLocale($locale);
	
				return $resourceBundle;
			}
		}
	}

	/**
	 * Returns an enumeration of the keys.
	 *
	 * @return array
	 */
	public function getKeys() {
		return array_keys($this->properties);
	}

	/**
	 * Returns the locale of this resource bundle.
	 *
 	 * @return Locale
	 */
	public function getLocale() {
		return $this->locale;
	}

	/**
	 * Gets an object for the given key from this resource bundle or one of its parents.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function getObject($key) {
		return $this->properties[$key];
	}

	/**
	 * Gets a string for the given key from this resource bundle or one of its parents.
	 *
	 * @param string $key
	 * @return string
	 */
	public function getString($key) {
		return strval($this->properties[$key]);
	}

	/**
	 * Gets a string array for the given key from this resource bundle or one of its parents.
	 *
	 * @param string $key
	 * @return array
	 */
	public function getStringArray($key) {
	}

	/**
	 * Sets the parent bundle of this bundle.
	 *
	 * @param ResourceBundle $parent
	 */
	public function setParent(ResourceBundle $parent) {
		$this->parent = $parent;
	}

	/**
	 * @param Locale $locale
	 */
	public function setLocale(Locale $locale) {
		$this->locale = $locale;
	}
}
	
?>
