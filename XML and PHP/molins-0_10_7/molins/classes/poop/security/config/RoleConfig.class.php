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
 * @version $Id: RoleConfig.class.php,v 1.2 2005/10/12 21:01:00 slizardo Exp $
 * @package poop.security.config
 */

import('poop.security.config.PermissionConfig');

class RoleConfig {

	private $name;
	private $permissions;

	public function __construct() {
		$this->permissions = array();
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param PermissionConfig $permission
	 */
	public function addPermission(PermissionConfig $permission) {
		array_push($this->permissions, $permission);
	}

	/**
	 * @return array
	 */
	public function getPermissions() {
		return $this->permissions;
	}
}

?>
