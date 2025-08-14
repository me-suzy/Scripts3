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
 * @version $Id: Locale.class.php,v 1.10 2005/10/13 16:27:33 slizardo Exp $
 * @package poop.util
 */

class Locale {

	private $language;
	private $country;
	private $variant;
	
	/**
	 * @param string $lang
	 */
	public static function init($lang) {
		bindtextdomain('molins', CONF_DIR_MOLINS.DIRECTORY_SEPARATOR.'locale');
		textdomain('molins');
		putenv('LANG='.$lang);
		setlocale(LC_ALL, $lang);		
	}
	
	/**
	 * @param string $language
	 * @param string $country
	 * @param string $variant
	 */
	public function __construct($language = null, $country = null, $variant = null) {
		$this->language = $language;
		$this->country = $country;
		$this->variant = $variant;		
	}

	/**
	 * @return string
	 */
	public function getCountry() {
		return $this->country;
	}

	/**
	 * @return Locale
	 */
	public static function getDefault() {
		return new Locale('en', 'US');
	}

	/**
	 * @return array
	 */
	public static function getISOCountries() {
	}

	/**
	 * @return array
	 */
	public static function getISOLanguages() {
	}
	
	/**
	 * @return string
	 */
	public function getLanguage() {
		return $this->language;
	}

	/**
	 * @return string
	 */
	public function getVariant() {
		return $this->variant;
	}

	/**
	 * @param Locale $locale
	 */
	public static function setDefault(Locale $locale) {
	}

	/**
	 * @param mixed $object
	 * @return boolean
	 */
	public function equals($object) {
		if($object instanceof Locale) {
			return (
				$object->getLanguage() == $this->language &&
				$object->getCountry() == $this->country &&
				$object->getVariant() == $this->variant
			);
		}

		return false;
	}

	public function __toString() {
		$buffer = new StringBuffer();
		$buffer->append($this->language);
		if($this->country != null) {
			$buffer->append($this->country);
			if($this->variant != null) {
				$buffer->append($this->variant);
			}
		}

		return $buffer->__toString();
	}
}

?>
