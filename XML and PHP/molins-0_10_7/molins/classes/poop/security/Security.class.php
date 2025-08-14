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
 * @version $Id: Security.class.php,v 1.9 2005/10/12 21:01:00 slizardo Exp $
 * @package poop.security
 */

import('poop.cache.CacheUtil');
import('poop.security.config.*');

Security::init();

final class Security {

	private static $store;	
	
	public static function init() {
		$xmlPath = implode(DIRECTORY_SEPARATOR, array(CONF_DIR_APP, 'WEB-INF', 'security.xml'));

		/**
		 * If the file WEB-INF/security.xml does not exist , we dont do nothing.
		 */
		if(!file_exists($xmlPath)) {
			return;
		}

		$xsdPath = implode(DIRECTORY_SEPARATOR, array(CONF_DIR_MOLINS, 'schemas', 'security.xsd'));
		$serPath = implode(DIRECTORY_SEPARATOR, array(CONF_DIR_APP, 'WEB-INF', 'work', 'security.ser'));
		
		if(filemtime($xmlPath) >= time()-CacheUtil::T_MINUTE && file_exists($serPath)) {
			$serialized = file_get_contents($serPath);
			self::$store = unserialize($serialized);
			return;
		}

		self::$store = new SecurityConfig();
		
		$dom = new DomDocument();
		if(!@$dom->load($xmlPath)) {
			throw new XMLException(basename($xmlPath));
		}
		if(!@$dom->schemaValidate($xsdPath)) {
			throw new XMLException(_('error validando esquema'), basename($xsdPath));
		}
		
		$xPath = new DomXpath($dom);

		$roles = $xPath->query('/security/roles/role');
		for($i = 0; $i < $roles->length; $i++) {
			$role = $roles->item($i);

			$roleConfig = new RoleConfig();
			$roleConfig->setName($role->getAttribute('name'));

			$permissions = $xPath->query('./permission', $role);
			for($p = 0; $p < $permissions->length; $p++) {
				$permission = $permissions->item($p);

				$permissionConfig = new PermissionConfig();
				$permissionConfig->setModule($permission->getAttribute('module'));
				$permissionConfig->setFunction($permission->getAttribute('function'));

				$roleConfig->addPermission($permissionConfig);
			}

			self::$store->addRole($roleConfig);
		}

		$users = $xPath->query('/security/users/user');
		for($i = 0; $i < $users->length; $i++) {
			$user = $users->item($i);

			$userConfig = new UserConfig();
			$userConfig->setName($user->getAttribute('name'));
			$userConfig->setPassword($user->getAttribute('password'));

			$userRoles = $xPath->query('./user-role', $user);
			for($r = 0; $r < $userRoles->length; $r++) {
				$userRole = $userRoles->item($r);

				$userRoleConfig = new UserRoleConfig();
				$userRoleConfig->setName($userRole->getAttribute('name'));

				$userConfig->addUserRole($userRoleConfig);
			}
			
			self::$store->addUser($userConfig);
		}

		$serialized = serialize(self::$store);
		file_put_contents($serPath, $serialized);
	}

	/**
	 * @param string $name
	 * @param string $password
	 * @param string $module
	 * @param string $function
	 * @return boolean
	 */
	public static function isAllowed($name, $password, $module = '*', $function = '*') {
		foreach(self::$store->getUsers() as $user) {
			if($user->getName() == $name && $user->getPassword() == $password) {
				return true;
			}
		}

		return false;
	}
}

?>
