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
 * @version $Id: HttpSession.class.php,v 1.9 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.http
 */

final class HttpSession {
	const SESSION_NAME = 'MOLINS_SESSION';

	/**
	 * Inicializa la sesion.
	 */
	public function __construct() {
		session_name(self::SESSION_NAME);
		session_start();
	}
	
	/**
	 * Recupera una variable de la sesion, o null si no existe.
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function &getAttribute($name) {
		if(session_is_registered($name)) {
			return $_SESSION[$name];
		} else {
			return null;
		}
	}

	/**
	 * Agrega o reemplaza una variable en la sesion.
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function setAttribute($name, $value) {
		$_SESSION[$name] = $value;
		session_register($name);
	}

	/**
	 * Borra un atributo de la sesion actual.
	 *
	 * @param string $name
	 */
	public function removeAttribute($name) {
		session_unregister($name);
	}

	/**
	 * Indica si una variable fue definida o no en la sesion.
	 *
	 * <code>
	 * <?php
	 * if(HttpSession::isRegistered('usuario') == false) {
	 *	$usuario = array('slizardo', 'santiago.lizardo@gmail.com');
	 * 	HttpSession::setAttribute('usuario', $usuario);
	 * }
	 * ?>
	 * </code>
	 * @param string $name
	 * @return boolean
	 */
	public function isRegistered($name) {
		return session_is_registered($name);
	}

	public function destroy() {
		session_destroy();
	}
}

?>
