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
 * @version $Id: PermissionConfig.class.php,v 1.2 2005/10/12 21:01:00 slizardo Exp $
 * @package poop.security.config
 */

class PermissionConfig {

	private $module;
	private $function;

	public function __construct() {
	}

	/**
	 * @param string $module
	 */
	public function setModule($module) {
		$this->module = $module;
	}

	/**
	 * @return string
	 */	
	public function getModule() {
		return $this->module;
	}

	/**
	 * @param string $function
	 */
	public function setFunction($function) {
		$this->function = $function;
	}

	/**
	 * @return string
	 */
	public function getFunction() {
		return $this->function;
	}
}

?>
