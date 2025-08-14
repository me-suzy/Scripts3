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
 * @version $Id: Appender.class.php,v 1.7 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.logging
 */

/**
 * Esta clase representa un registrador de logs, es abstracta por que las clases hijas<br />
 * deben implementar la logica propia de cada funcionalidad.
 */ 
abstract class Appender {

	/**
 	 * La variable layout tendra un objeto de tipo Layout,<br />
	 * que indicara la forma en que se escribira cada linea de log.<br />
	 * Por defecto, SimpleLayout.
	 *
	 * @see poop.logging.Layout
	 */
	private $layout;

	/**
	 * Un array de tipo hash que almacenera en las keys, los nombre de las propiedades<br />
	 * indicadas en el logging.xml, y los valores correspondientes.
	 */
	private $properties;
	
	/**
	 * Constructor, incializa el array de propiedades.
	 */
	public function __construct() {
		$this->properties = array();
	}

	/**
	 * Agrega o reemplaza una propiedad del appender.
	 *
	 * @param string $name
	 * @param string $value
	 */
	public function setProperty($name, $value) {
		$this->properties[$name] = $value;
	}
	
	/**
	 * Devuelve una propiedad del appender.
	 *
	 * @param string $name
	 * @return string
	 */
	public function getProperty($name) {
		return $this->properties[$name];
	}

	/**
	 * Asigna el layout que utilizara este appender.
 	 *
	 * @param Layout $layout
	 */	 
	public function setLayout(Layout $layout) {
		$this->layout = $layout;
	}

	/**
	 * Devuelve el layout utilizado por este appender.
	 *
	 * @return Layout
	 */
	public function getLayout() {
		return $this->layout;
	}

	/**
	 * Funcion que puede ser sobrecargada, encargada de inicializar<br />
	 * recursos (files, sockets, etc).
	 */
	public function init() {
	}
	
	/**
	 * Metodo a implementar por cada appender, se encargara de hacer el log de la manera
	 * que le corresponde en cada caso (bbdd, ficheros, sockets, etc...) 
	 *
	 * @param int $level
	 * @param string $message
	 * @param Exception $exception
	 */
	public abstract function log($level, $message, $exception = null);
}
	
?>
