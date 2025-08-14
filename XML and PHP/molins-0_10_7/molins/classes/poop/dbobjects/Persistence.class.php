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
 * @version $Id: Persistence.class.php,v 1.9 2005/10/12 21:00:24 slizardo Exp $
 * @package poop.dbobjects
 */

import('poop.cache.CacheUtil');
import('poop.dbobjects.config.*');
	
final class Persistence {

	private static $store;
	
	public static function init() {
		$xmlPath = implode(DIRECTORY_SEPARATOR, array(CONF_DIR_APP, 'WEB-INF', 'persistence.xml'));

		/**
		 * If the file WEB-INF/persistence.xml does not exist , we dont do nothing.
		 */
		if(!file_exists($xmlPath)) {
			return;
		}

		$xsdPath = implode(DIRECTORY_SEPARATOR, array(CONF_DIR_MOLINS, 'schemas', 'persistence.xsd'));
		$serPath = implode(DIRECTORY_SEPARATOR, array(CONF_DIR_APP, 'WEB-INF', 'work', 'persistence.ser'));
		
		if(filemtime($xmlPath) >= time()-CacheUtil::T_MINUTE && file_exists($serPath)) {
			$serialized = file_get_contents($serPath);
			self::$store = unserialize($serialized);
			return;
		}

		self::$store = new PersistenceConfig();
		
		$dom = new DomDocument();
		if(!@$dom->load($xmlPath)) {
			throw new XMLException(basename($xmlPath));
		}
		if(!@$dom->schemaValidate($xsdPath)) {
			throw new XMLException(_('error validando esquema'), basename($xsdPath));
		}
		
		$xPath = new DomXpath($dom);
		
		$objects = $xPath->query('/persistence/object');
		for($i = 0; $i < $objects->length; $i++) {
			$object = $objects->item($i);

			$objectConfig = new ObjectConfig();
			$objectConfig->setClass($object->getAttribute('class'));

			$table = $xPath->query('./table', $object)->item(0);
			$objectConfig->setTable($table->textContent);

			$primaryKey = $xPath->query('./primary-key', $object)->item(0);
			$objectConfig->setPrimaryKey($primaryKey->textContent);

			self::$store->addObject($objectConfig);
		}

		$groups = $xPath->query('/persistence/group');
		for($i = 0; $i < $groups->length; $i++) {
			$group = $groups->item($i);

			$groupConfig = new GroupConfig();
			$groupConfig->setClass($group->getAttribute('class'));

			$object = $xPath->query('./object', $group)->item(0);
			$groupConfig->setObject($object->textContent);

			self::$store->addGroup($groupConfig);
		}

		$serialized = serialize(self::$store);
		file_put_contents($serPath, $serialized);
	}

	/**
	 * @param string $class
	 * @return ObjectConfig
	 */
	public static function getObject($class) {
		foreach(self::$store->getObjects() as $object) {
			if($object->getClass() == $class) {
				return $object;
			}
		}

		return null;
	}

	/**
	 * @param string $class
	 * @return GroupConfig
	 */
	public static function getGroup($class) {
		foreach(self::$store->getGroups() as $group) {
			if($group->getClass() == $class) {
				return $group;
			}
		}

		return null;
	}
	
	/**
	 * @return array
	 */	
	public static function getJoinsFor() {
		return array();
	}
}

Persistence::init();

?>
