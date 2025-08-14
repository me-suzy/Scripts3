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
 * @version $Id: UserConfig.class.php,v 1.3 2005/10/12 21:01:00 slizardo Exp $
 * @package poop.security.config
 */

import('poop.security.config.UserRoleConfig');

class UserConfig {

	private $name;
	private $password;
	private $roles;

	public function __construct() {
		$this->roles = array();
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
	 * @param string $password
	 */
	public function setPassword($password) {
		$this->password = $password;
	}

	/**
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @param UserRoleConfig $role
	 */
	public function addUserRole(UserRoleConfig $role) {
		array_push($this->roles, $role);
	}

	/**
	 * @return array
	 */
	public function getRoles() {
		return $this->roles;
	}
}

?>
