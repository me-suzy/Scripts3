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
 * @version $Id: PersistenceConfig.class.php,v 1.3 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.dbobjects.config
 */

import('poop.dbobjects.config.ObjectConfig');
import('poop.dbobjects.config.GroupConfig');

class PersistenceConfig {

	private $objects;
	private $groups;
	
	public function __construct() {
		$this->objects = array();
		$this->groups = array();
	}

	/**
	 * @param ObjectConfig $object
	 */
	public function addObject(ObjectConfig $object) {
		array_push($this->objects, $object);
	}

	/**
	 * @return array
	 */
	public function getObjects() {
		return $this->objects;
	}

	/**
	 * @param GroupConfig $group
	 */
	public function addGroup(GroupConfig $group) {
		array_push($this->groups, $group);
	}

	/**
	 * @return array
	 */
	public function getGroups() {
		return $this->groups;
	}
}

?>
