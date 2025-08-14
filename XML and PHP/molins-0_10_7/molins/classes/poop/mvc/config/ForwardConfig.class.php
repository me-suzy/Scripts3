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
 * @version $Id: ForwardConfig.class.php,v 1.5 2005/10/12 21:00:43 slizardo Exp $
 * @package poop.mvc.config
 */

class ForwardConfig {

	private $name;
	private $path;
	private $redirect;

	/**
	 * @param string $name
	 * @param string $path
	 * @param boolean $redirect
	 */
	public function __construct($name = null, $path = null, $redirect = false) {
		$this->name = $name;
		$this->path = $path;
		$this->redirect = $redirect;
	}

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
	 * Devuelve el valor de la propiedad redirect.
	 *
	 * @return string
	 */
	public function getRedirect() {
		return $this->redirect;
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad redirect.
	 *
	 * @param boolean $redirect
	 */
	public function setRedirect($redirect) {
		$this->redirect = $redirect;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		$sb = new StringBuffer('ForwardConfig[');
		$sb->append('name=');
		$sb->append($this->name);
		$sb->append(',path=');
		$sb->append($this->path);
		$sb->append(',redirect=');
		$sb->append($this->redirect);
		$sb->append(']');
		
		return $sb->__toString();		
	}
}
	
?>
