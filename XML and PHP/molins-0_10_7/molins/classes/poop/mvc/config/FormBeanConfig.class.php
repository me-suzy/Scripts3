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
 * @version $Id: FormBeanConfig.class.php,v 1.4 2005/10/12 21:00:43 slizardo Exp $
 * @package poop.mvc.config
 */

class FormBeanConfig {

	private $formProperties;

	private $dynaActionFormClass;

	private $dynamic;

	private $name;

	private $type;

	/**
	 * Devuelve el valor de la propiedad name.
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad name.
	 *
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Devuelve el valor de la propiedad type.
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad type.
	 *
	 * @param string $type
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * @param mixed $servlet
	 */
	public function createActionForm($servlet) {
	}

	/**
	 * @param FormPropertyConfig $formPropertyConfig
	 */
	public function addFormPropertyConfig(FormPropertyConfig $formPropertyConfig) {
	}

	/**
	 * @param string $name
	 * @return FormPropertyConfig
	 */
	public function findFormPropertyConfig($name) {
	}

	public function findFormPropertyConfigs() {
	}

	/**
	 * @param string $name
	 */
	public function removeFormPropertyConfig($name) {
	}

	/**
	 * @return string
	 */
	public function __toString() {
	}
}
	
?>
