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
 * @version $Id: GroupConfig.class.php,v 1.3 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.dbobjects.config
 */

class GroupConfig {

	private $class;
	private $object;

	/**
	 * @return string
	 */
	public function getClass() {
		return $this->class;
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
	public function getObject() {
		return $this->object;
	}

	/**
	 * @param string $object
	 */
	public function setObject($object) {
		$this->object = $object;
	}	
}

?>
