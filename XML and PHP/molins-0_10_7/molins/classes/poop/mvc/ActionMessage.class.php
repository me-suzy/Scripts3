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
 * @version $Id: ActionMessage.class.php,v 1.6 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.mvc
 */

class ActionMessage {

	private $key;
	private $value;

	/**
	 * @param string $key
	 */
	public function __construct($key) {
		$this->key = $key;
	}
	
	/**
	 * Devuelve el valor de la propiedad key.
	 *
	 * @return string
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad key.
	 *
	 * @param string $key
	 */
	public function setKey($key) {
		$this->key = $key;
	}

	/**
	 * Devuelve el valor de la propiedad value.
	 *
	 * @return string
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad value.
	 *
	 * @param mixed $value
	 */
	public function setValue($value) {
		$this->value = $value;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->key;
	}
}
	
?>
