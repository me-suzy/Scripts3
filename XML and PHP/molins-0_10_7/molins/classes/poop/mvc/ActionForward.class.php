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
 * @version $Id: ActionForward.class.php,v 1.6 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.mvc
 */

class ActionForward {

	private $name;
	private $path;

	/**
	 * @param string $name
	 * @param string $path
	 */
	public function __construct($name, $path) {
		$this->name = $name;
		$this->path = $path;
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
}

?>
