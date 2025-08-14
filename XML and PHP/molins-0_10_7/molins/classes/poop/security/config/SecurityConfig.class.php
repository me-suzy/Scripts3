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
 * @version $Id: SecurityConfig.class.php,v 1.2 2005/10/12 21:01:00 slizardo Exp $
 * @package poop.security.config
 */

import('poop.security.config.RoleConfig');
import('poop.security.config.UserConfig');

class SecurityConfig {

	private $roles;
	private $users;

	public function __construct() {
		$this->roles = array();
		$this->users = array();
	}

	/**
	 * @param RoleConfig $role
	 */
	public function addRole(RoleConfig $role) {
		array_push($this->roles, $role);
	}

	/**
	 * @return array
	 */
	public function getRoles() {
		return $this->roles;
	}

	/**
	 * @param UserConfig $user
	 */
	public function addUser(UserConfig $user) {
		array_push($this->users, $user);
	}
	
	/**
	 * @return array
	 */
	public function getUsers() {
		return $this->users;
	}
}

?>
