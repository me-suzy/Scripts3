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
 * @version $Id: ActionConfig.class.php,v 1.7 2005/10/12 21:00:43 slizardo Exp $
 * @package poop.mvc.config
 */

import('poop.mvc.config.ForwardConfig');

class ActionConfig {

	private $forwards;
	private $attribute;
	private $forward;
	private $include;
	private $input;
	private $name;
	private $parameter;
	private $path;
	private $prefix;
	private $scope;
	private $suffix;
	private $type;
	private $validate;

	public function __construct() {
		$this->forwards = array();
	}
	
	/**
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
	 * Devuelve el valor de la propiedad parameter.
	 *
	 * @return string
	 */
	public function getParameter() {
		return $this->parameter;
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad parameter.
	 *
	 * @param string $parameter
	 */
	public function setParameter($parameter) {
		$this->parameter = $parameter;
	}
	
	/**
	 * Devuelve el valor de la propiedad path.
	 *
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad path.
	 *
	 * @param string $path
	 */
	public function setPath($path) {
		$this->path = $path;
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
	 * Devuelve el valor de la propiedad scope.
	 *
	 * @return string
	 */
	public function getScope() {
		return $this->scope;
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad scope.
	 *
	 * @param string $scope
	 */
	public function setScope($scope) {
		$this->scope = $scope;
	}

	/**
	 * Devuelve el valor de la propiedad validate.
	 *
	 * @return string
	 */
	public function getValidate() {
		return $this->validate;
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad validate.
	 *
	 * @param string $validate
	 */
	public function setValidate($validate) {
		$this->validate = $validate;
	}
	
	/**
	 * @param ForwardConfig $forwardConfig
	 */
	public function addForward(ForwardConfig $forwardConfig) {
		$this->forwards[$forwardConfig->getName()] = $forwardConfig;
	}

	/**
	 * @param string $name
	 * @return ForwardConfig
	 */
	public function findForward($name) {
		return $this->forwards[$name];
	}

	/**
	 * @return array
	 */
	public function findForwardConfigs() {
		return $this->forwards;
	}

	/**
	 * @param ForwardConfig $forwardConfig
	 */
	public function removeForwardConfig(ForwardConfig $forwardConfig) {
		unset($this->forwards[$forwardConfig->getName()]);
	}

	/**
	 * @return string
	 */
	public function __toString() {
	}
}
	
?>
