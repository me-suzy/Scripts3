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
 * @version $Id: AppenderConfig.class.php,v 1.3 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.logging.config
 */

import('poop.logging.config.PropertyConfig');
import('poop.logging.config.LayoutConfig');
 
class AppenderConfig {

	private $class;
	private $properties;
	private $layout;

	public function __construct() {
		$this->properties = array();
	}

	/**
	 * @param string $class
	 */
	public function setClass($class) {
		$this->class = $class;
	}

	/**
	 * @return string
	 */
	public function getClass() {
		return $this->class;
	}

	/**
	 * @param PropertyConfig $property
	 */
	public function addProperty(PropertyConfig $property) {
		array_push($this->properties, $property);
	}

	/**
	 * @return array
	 */
	public function getProperties() {
		return $this->properties;
	}

	/**
	 * @param LayoutConfig $layout
	 */
	public function setLayout(LayoutConfig $layout) {
		$this->layout = $layout;
	}

	/**
	 * @return LayoutConfig
	 */
	public function getLayout() {
		return $this->layout;
	}
}
	
?>
