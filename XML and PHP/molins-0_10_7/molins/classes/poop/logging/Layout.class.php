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
 * @version $Id: Layout.class.php,v 1.6 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.logging
 */

/**
 * Clase abstracta que permite construir clases<br />
 * que daran formato a los mensajes de log.
 */
abstract class Layout {

	/**
	 * Array que almacenera las posibles propiedades de un layout.<br />
	 * (pasadas por el logging.xml)
	 */
	private $properties;

	/**
	 * El constructor inicializa el array de propiedades.
	 */
	public function __construct() {
		$this->properties = array();
	}

	/**
	 * Poner una propiedad y su valor a este layout.
	 *
	 * @param string $name
	 * @param string $value
	 */
	public function setProperty($name, $value) {
		$this->properties[$name] = $value;
	}
	
	/**
	 * Recupera el valor de una propiedad de este layout.
	 *
	 * @param string el nombre de la propiedad
	 * @return mixed el valor de la propiedad
	public function getProperty($name) {
		return $this->properties[$name];
	}

	/**
	 * Pregunta si una propiedad existe en este layout.
	 *
	 * @param string $name
	 * @return boolean
	 */
	public function propertyExists($name) {
		return array_key_exists($name, $this->properties);
	}
	
	/**
	 * Funcion a implementar por las clases hijas,<br />
	 * que se encargara de formatear el mensaje.
	 *
	 * @param int $level
	 * @param string $message
	 * @param Exception $exception
	 */
	public abstract function format($level, $message, $exception);
}

?>
