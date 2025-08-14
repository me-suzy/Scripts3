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
 * @version $Id: ApplicationConfig.class.php,v 1.8 2005/10/12 21:00:43 slizardo Exp $
 * @package poop.mvc.config
 */

import('poop.cache.CacheUtil');

final class ApplicationConfig {

	private static $store;

	private $formBeans;
	private $actionMappings;
	
	public static function init() {
		$xmlPath = implode(DIRECTORY_SEPARATOR, array(CONF_DIR_APP, 'WEB-INF', 'mvc-config.xml'));

		/**
		 * If the file WEB-INF/mvc-config.xml does not exist , we dont do nothing.
		 */
		if(!file_exists($xmlPath)) {
			return;
		}

		$xsdPath = implode(DIRECTORY_SEPARATOR, array(CONF_DIR_MOLINS, 'schemas', 'mvc-config.xsd'));
		$serPath = implode(DIRECTORY_SEPARATOR, array(CONF_DIR_APP, 'WEB-INF', 'work', 'mvc-config.ser'));
		
		if(filemtime($xmlPath) >= time()-CacheUtil::T_MINUTE && file_exists($serPath)) {
			$serialized = file_get_contents($serPath);
			self::$store = unserialize($serialized);
			return;
		}

		self::$store = new ApplicationConfig();
		
		$dom = new DomDocument();
		if(!@$dom->load($xmlPath)) {
			throw new XMLException(basename($xmlPath));
		}
		if(!@$dom->schemaValidate($xsdPath)) {
			throw new XMLException(_('error validando esquema'), basename($xsdPath));
		}
		
		$xPath = new DomXpath($dom);
	
		$formBeans = $xPath->query('/mvc-config/form-beans/form-bean');
		for($i = 0; $i < $formBeans->length; $i++) {
			$formBean = $formBeans->item($i);

			$formBeanConfig = new FormBeanConfig();
			$formBeanConfig->setName($formBean->getAttribute('name'));
			$formBeanConfig->setType($formBean->getAttribute('type'));			
			
			self::$store->addFormBean($formBeanConfig);
		}

		$actionMappings = $xPath->query('/mvc-config/action-mappings/action');
		for($i = 0; $i < $actionMappings->length; $i++) {
			$actionMapping = $actionMappings->item($i);

			$actionMappingConfig = new ActionConfig();
			$actionMappingConfig->setPath($actionMapping->getAttribute('path'));
			$actionMappingConfig->setType($actionMapping->getAttribute('type'));
			$actionMappingConfig->setName($actionMapping->getAttribute('name'));
			$actionMappingConfig->setScope($actionMapping->getAttribute('scope'));
			$actionMappingConfig->setValidate($actionMapping->getAttribute('validate'));
			$actionMappingConfig->setParameter($actionMapping->getAttribute('parameter'));			

			$forwards = $xPath->query('./forward', $actionMapping);
			for($f = 0; $f < $forwards->length; $f++) {
				$forward = $forwards->item($f);

				$forwardConfig = new ForwardConfig();
				$forwardConfig->setName($forward->getAttribute('name'));
				$forwardConfig->setPath($forward->getAttribute('path'));
				$forwardConfig->setRedirect($forward->getAttribute('redirect'));				

				$actionMappingConfig->addForward($forwardConfig);
			}
			
			self::$store->addActionMapping($actionMappingConfig);
		}

		$serialized = serialize(self::$store);
		file_put_contents($serPath, $serialized);
	}

	/**
	 * @param string $path
	 * @return ActionConfig
	 */
	public static function getActionByPath($path) {
		foreach(self::$store->getActionMappings() as $actionMapping) {
			if($actionMapping->getPath() == $path) {
				return $actionMapping;
			}
		}
		
		throw new NullPointerException($path);
	}

	/**
	 * @param string $name
	 * @return FormBeanConfig
	 */
	public static function getFormBeanByName($name) {
		foreach(self::$store->getFormBeans() as $formBean) {
			if($formBean->getName() == $name) {
				return $formBean;
			}
		}
		
		throw new NullPointerException($name);
	}

	public function __construct() {
		$this->formBeans = array();
		$this->actionMappings = array();
	}

	/**
	 * @param FormBeanConfig $formBean
	 */
	public function addFormBean(FormBeanConfig $formBean) {
		array_push($this->formBeans, $formBean);
	}
	
	/**
	 * @return array
	 */
	public function getFormBeans() {
		return $this->formBeans;
	}

	/**
	 * @param ActionConfig $actionMapping
	 */
	public function addActionMapping(ActionConfig $actionMapping) {
		array_push($this->actionMappings, $actionMapping);
	}

	/**
	 * @return array
	 */
	public function getActionMappings() {
		return $this->actionMappings;
	}
}
	
?>
