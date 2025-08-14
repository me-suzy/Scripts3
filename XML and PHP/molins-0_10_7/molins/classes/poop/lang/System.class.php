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
 * @version $Id: System.class.php,v 1.6 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.lang
 */

import('poop.io.InputStream');
import('poop.io.PrintStream');
 
final class System {

	public static $in; // InputStream
	public static $out; // PrintStream
	public static $err; // PrintStream

	/**
	 * @param InputStream $in
	 */
	public static function setIn(InputStream $in) {
		$this->in = $in;
	}
	
	/**
	 * @param PrintStream $out
	 */
	public static function setOut(PrintStream $out) {
		$this->out = $out;
	}
	
	/**
	 * @param PrintStream $err
	 */
	public static function setErr(PrintStream $err) {
		$this->err = $err;
	}
	
	/**
	 * @param string $name
	 * @return string
	 */
	public static function getenv($name) {
		if(array_key_exists($name, $_ENV)) {
			return $_ENV[$name];
		} else {
			return null;
		}
	}
	
	/**
	 * @param string $libname
	 */
	public static function loadLibrary($libname) {
		dl($libname);
	}
	
	/**
	 * @param int $status
	 */
	public static function exitApp($status = 0) {
		exit($status);
	}
}
	
?>
